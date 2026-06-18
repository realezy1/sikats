<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'user'])
            ->whereIn('status', ['unpaid', 'proses', 'ready'])
            ->latest()
            ->get();
        return view('cashier.orders.index', compact('orders'));
    }

    public function activeData()
    {
        $orders = Order::with(['table', 'user'])
            ->whereIn('status', ['unpaid', 'proses', 'ready'])
            ->latest()
            ->get();
        return view('cashier.orders.partials.order_list', compact('orders'));
    }

    public function create()
    {
        // Hanya ambil meja yang aktif DAN tidak sedang memiliki order dengan status unpaid/proses/ready
        $tables = Table::where('is_active', true)
            ->whereDoesntHave('orders', function($query) {
                $query->whereIn('status', ['unpaid', 'proses', 'ready']);
            })->get();
            
        return view('cashier.orders.create', compact('tables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'nullable|string|max:100',
        ]);

        // Cek kembali di backend untuk memastikan meja belum diambil kasir lain
        $isTableOccupied = Order::where('table_id', $request->table_id)
            ->whereIn('status', ['unpaid', 'proses', 'ready'])
            ->exists();

        if ($isTableOccupied) {
            return back()->withErrors(['table_id' => 'Maaf, meja ini sedang digunakan. Silakan pilih meja lain.'])->withInput();
        }

        $orderId = 'TRX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        while(Order::where('id', $orderId)->exists()) {
            $orderId = 'TRX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        $order = Order::create([
            'id' => $orderId,
            'table_id' => $request->table_id,
            'customer_name' => $request->customer_name,
            'user_id' => Auth::id(),
            'source' => 'staff',
            'status' => 'unpaid',
        ]);

        return redirect()->route('cashier.orders.show', $order->id)->with('success', 'Order berhasil dibuat. Silakan tambahkan menu.');
    }

    public function show(Order $order)
    {
        $categories = Category::with(['menus' => function($q) {
            $q->where('is_active', true);
        }])->get();
        
        $order->load(['items.menu', 'table']);

        confirmDelete('Hapus Item!', 'Apakah Anda yakin ingin menghapus item ini dari pesanan?');

        return view('cashier.orders.show', compact('order', 'categories'));
    }

    public function addItem(Request $request, Order $order)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        if ($menu->stock < $request->quantity) {
            return back()->withErrors(['error' => 'Stok menu tidak mencukupi. Sisa stok: ' . $menu->stock]);
        }

        $existingItem = $order->items()->where('menu_id', $menu->id)->where('status', 0)->first();
        
        if ($existingItem) {
            $existingItem->update([
                'quantity' => $existingItem->quantity + $request->quantity,
                'note' => $request->note ? ($existingItem->note ? $existingItem->note . ', ' . $request->note : $request->note) : $existingItem->note
            ]);
        } else {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'quantity' => $request->quantity,
                'price_at_order' => $menu->price,
                'note' => $request->note,
                'status' => 0
            ]);
        }

        $menu->decrement('stock', $request->quantity);

        return back()->with('success', 'Item berhasil ditambahkan.');
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        if ($item->order_id !== $order->id) {
            abort(403);
        }

        if ($item->status > 0) {
            return back()->withErrors(['error' => 'Item sudah diproses di dapur, tidak dapat dihapus.']);
        }

        $item->menu->increment('stock', $item->quantity);
        $item->delete();

        return back()->with('success', 'Item berhasil dihapus dari pesanan.');
    }

    public function checkout(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,midtrans',
            'cash_amount' => 'required_if:payment_method,cash|numeric|min:' . $order->total
        ]);

        if ($order->status !== 'unpaid' || $order->items->isEmpty()) {
            return back()->withErrors(['error' => 'Order tidak valid untuk di-checkout.']);
        }

        if ($request->payment_method === 'cash') {
            $change = $request->cash_amount - $order->total;
            $order->update([
                'status' => 'proses',
                'payment_method' => 'cash',
                'cash_amount' => $request->cash_amount,
                'change_amount' => $change,
                'payment_time' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'redirect_url' => route('cashier.orders.receipt', $order->id)
            ]);
        }

        // Jika Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

        $params = [
            'transaction_details' => [
                'order_id' => $order->id . '-' . time(),
                'gross_amount' => $order->total,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name ?? 'Customer',
            ]
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
            return response()->json([
                'success' => true,
                'snap_token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function midtransCallback(Request $request, Order $order)
    {
        // This is called by frontend after Midtrans success
        $order->update([
            'status' => 'proses',
            'payment_method' => 'midtrans',
            'payment_time' => now(),
            'midtrans_order_id' => $request->input('order_id')
        ]);
        
        return response()->json(['success' => true]);
    }

    public function webhook(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = new \Midtrans\Notification();
        } catch (\Exception $e) {
            return response('Error', 500);
        }

        $transaction = $notification->transaction_status;
        $orderId = $notification->order_id;
        $realOrderId = explode('-', $orderId)[0] . '-' . explode('-', $orderId)[1] . '-' . explode('-', $orderId)[2]; // Extracts TRX-YYYYMMDD-XXXX

        $order = Order::find($realOrderId);

        if (!$order) {
            return response('Order not found', 404);
        }

        if ($transaction == 'capture' || $transaction == 'settlement') {
            $order->update([
                'status' => 'proses',
                'payment_method' => 'midtrans',
                'payment_time' => now(),
                'midtrans_order_id' => $orderId
            ]);
        } elseif ($transaction == 'cancel' || $transaction == 'deny' || $transaction == 'expire') {
            // Restore stock if needed or handle cancellation
        }

        return response('OK', 200);
    }

    public function receipt(Order $order)
    {
        if ($order->status === 'unpaid') {
            return redirect()->route('cashier.orders.show', $order->id)->withErrors(['error' => 'Pesanan belum dibayar.']);
        }
        
        $order->load(['items.menu', 'table', 'user']);
        
        // Return PDF view
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cashier.orders.receipt_pdf', compact('order'));
        // Return as inline PDF in browser
        return $pdf->stream('Struk-' . $order->id . '.pdf');
    }

    public function complete(Order $order)
    {
        if ($order->status !== 'ready') {
            return back()->withErrors(['error' => 'Pesanan harus berstatus Ready sebelum diselesaikan.']);
        }

        $order->update(['status' => 'completed']);

        return redirect()->route('cashier.orders.index')->with('success', 'Pesanan ' . $order->id . ' berhasil diselesaikan.');
    }

    public function destroy(Order $order)
    {
        if ($order->status !== 'unpaid') {
            return back()->withErrors(['error' => 'Hanya pesanan yang belum dibayar yang dapat dibatalkan.']);
        }

        // Kembalikan semua stok menu
        foreach ($order->items as $item) {
            if ($item->status == 0) { // Hanya kembalikan stok jika item belum diproses dapur
                $item->menu->increment('stock', $item->quantity);
            }
        }

        $order->delete();

        return redirect()->route('cashier.orders.index')->with('success', 'Pesanan ' . $order->id . ' berhasil dibatalkan dan meja telah dikosongkan.');
    }
}
