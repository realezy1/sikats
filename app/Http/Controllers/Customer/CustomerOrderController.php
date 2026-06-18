<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $tableId = $request->query('table');
        $token = $request->query('token');

        if (!$tableId || !$token) {
            abort(403, 'Akses ditolak: QR Code tidak valid.');
        }

        $table = Table::where('table_number', $tableId)
            ->where('qr_token', $token)
            ->where('is_active', true)
            ->first();

        if (!$table) {
            abort(403, 'Akses ditolak: Meja tidak ditemukan atau token tidak valid.');
        }

        // Cek apakah meja sedang dipakai
        $isOccupied = Order::where('table_id', $table->id)
            ->whereIn('status', ['unpaid', 'proses', 'ready'])
            ->exists();

        if ($isOccupied) {
            return response()->view('customer.error', [
                'message' => 'Meja ini sedang melayani pesanan aktif. Silakan selesaikan pesanan sebelumnya atau hubungi staf kami.'
            ], 403);
        }

        $categories = Category::orderBy('name')->get();
        // Hanya ambil menu yang aktif dan stok > 0
        $menus = Menu::with('category')
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('customer.order', compact('table', 'categories', 'menus'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,table_number',
            'token' => 'required|string',
            'customer_name' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.note' => 'nullable|string'
        ]);

        $table = Table::where('table_number', $request->table_id)
            ->where('qr_token', $request->token)
            ->first();

        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Token meja tidak valid.'], 403);
        }

        $isOccupied = Order::where('table_id', $table->id)
            ->whereIn('status', ['unpaid', 'proses', 'ready'])
            ->exists();

        if ($isOccupied) {
            return response()->json(['success' => false, 'message' => 'Meja sedang digunakan.'], 403);
        }

        DB::beginTransaction();

        try {
            $orderId = 'TRX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            while(Order::where('id', $orderId)->exists()) {
                $orderId = 'TRX-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }

            $order = Order::create([
                'id' => $orderId,
                'table_id' => $table->id,
                'customer_name' => $request->customer_name ?: 'Guest',
                'source' => 'customer',
                'status' => 'unpaid',
            ]);

            $totalPrice = 0;

            foreach ($request->items as $itemData) {
                $menu = Menu::lockForUpdate()->find($itemData['menu_id']);
                
                if ($menu->stock < $itemData['quantity']) {
                    throw new \Exception('Stok menu ' . $menu->name . ' tidak mencukupi.');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $itemData['quantity'],
                    'price_at_order' => $menu->price,
                    'note' => $itemData['note'] ?? null,
                    'status' => 0
                ]);

                $totalPrice += ($menu->price * $itemData['quantity']);
                $menu->decrement('stock', $itemData['quantity']);
            }

            // Integrasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $order->id . '-' . time(),
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => $order->customer_name,
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            DB::commit();

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function success(Order $order)
    {
        return view('customer.success', compact('order'));
    }
}
