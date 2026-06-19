<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month if no dates provided
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $reportData = $this->generateReportData($startDate, $endDate);

        return view('admin.reports.index', array_merge($reportData, [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]));
    }

    public function pdf(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $reportData = $this->generateReportData($startDate, $endDate);

        $data = array_merge($reportData, [
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        return $pdf->stream('Laporan-Penjualan-SiKats.pdf');
    }

    private function generateReportData($startDate, $endDate)
    {
        // Base query for completed orders in date range, eager load items to use the 'total' accessor
        $orders = Order::with('items')
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        // 1. Sales Summary
        $totalRevenue = $orders->sum('total');
        $totalTransactions = $orders->count();
        $avgTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // 2. Payment Methods
        $cashRevenue = $orders->where('payment_method', 'cash')->sum('total');
        $midtransRevenue = $orders->where('payment_method', 'midtrans')->sum('total');

        // 3. Top Selling Items
        $orderIds = $orders->pluck('id');

        $topItems = OrderItem::with('menu')
            ->whereIn('order_id', $orderIds)
            ->select('menu_id', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(quantity * price_at_order) as total_revenue'))
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // 4. Sales Chart Data (Group by Date)
        $chartData = $orders->groupBy(function ($order) {
            return $order->created_at->format('Y-m-d');
        })->map(function ($dayOrders) {
            return $dayOrders->sum('total');
        })->sortKeys();

        // Ensure we fill missing dates with 0 for a continuous chart line
        $dateLabels = [];
        $dateValues = [];

        if ($startDate->diffInDays($endDate) <= 60) {
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $dateStr = $currentDate->format('Y-m-d');
                $dateLabels[] = $currentDate->format('d M');
                $dateValues[] = $chartData->get($dateStr, 0);
                $currentDate->addDay();
            }
        }

        return [
            'totalRevenue' => $totalRevenue,
            'totalTransactions' => $totalTransactions,
            'avgTransaction' => $avgTransaction,
            'cashRevenue' => $cashRevenue,
            'midtransRevenue' => $midtransRevenue,
            'topItems' => $topItems,
            'chartLabels' => $dateLabels,
            'chartValues' => $dateValues
        ];
    }
}
