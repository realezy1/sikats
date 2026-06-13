<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;

use App\Models\Order;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        // Ambil transaksi yang sudah selesai (completed)
        $orders = Order::with(['table', 'user'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('cashier.sales.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->status !== 'completed') {
            abort(404, 'Transaksi tidak ditemukan di histori penjualan.');
        }

        $order->load(['items.menu', 'table', 'user']);

        return view('cashier.sales.show', compact('order'));
    }
}
