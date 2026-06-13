<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        // Fetch orders that are paid/in process ('proses') and have items pending (0) or cooking (1)
        $orders = Order::where('status', 'proses')
        ->whereHas('items', function ($query) {
            $query->whereIn('status', [0, 1]);
        })->with(['items' => function($query) {
            $query->whereIn('status', [0, 1])->with('menu');
        }, 'table'])
        ->orderBy('created_at', 'asc')
        ->get();

        return view('kitchen.index', compact('orders'));
    }

    public function updateStatus(Request $request, OrderItem $item)
    {
        $request->validate([
            'status' => 'required|in:1,2' // 1: Sedang Dimasak, 2: Siap Disajikan
        ]);

        if ($request->status == 1 && $item->status == 0) {
            $item->update([
                'status' => 1,
                'accepted_at' => now()
            ]);
        } elseif ($request->status == 2 && $item->status == 1) {
            $item->update([
                'status' => 2,
                'ready_at' => now()
            ]);

            // Check if all items in this order are now done (status == 2)
            $order = $item->order;
            $hasUnfinished = $order->items()->whereIn('status', [0, 1])->exists();
            if (!$hasUnfinished) {
                $order->update([
                    'status' => 'ready'
                ]);
            }
        }

        return back()->with('success', 'Status item diperbarui');
    }
}
