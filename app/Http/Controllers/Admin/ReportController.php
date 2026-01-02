<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Display the sales report.
     */
    public function index(Request $request): View
    {
        $data = $this->getReportData($request);
        return view('admin.reports.index', $data);
    }



    /**
     * Get report data based on request.
     */
    private function getReportData(Request $request): array
    {
        $period = $request->get('period', 'today');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Set date range based on period
        switch ($period) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday();
                break;
            case 'week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'custom':
                $startDate = $startDate ? Carbon::parse($startDate) : Carbon::today();
                $endDate = $endDate ? Carbon::parse($endDate) : Carbon::today();
                break;
            default:
                $startDate = Carbon::today();
                $endDate = Carbon::today();
        }

        // Get orders for the period
        $orders = Order::with(['table', 'orderItems.menu'])
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('payment_status', 'paid')
            ->get();

        // Calculate statistics
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Get top selling menus
        $topMenus = Menu::withCount(['orderItems' => function($query) use ($startDate, $endDate) {
            $query->whereHas('order', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                  ->where('payment_status', 'paid');
            });
        }])
        ->orderBy('order_items_count', 'desc')
        ->take(10)
        ->get();

        // Get daily revenue for chart
        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get orders by status
        $ordersByStatus = Order::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'averageOrderValue',
            'topMenus',
            'dailyRevenue',
            'ordersByStatus',
            'startDate',
            'endDate',
            'period'
        );
    }

    /**
     * Export sales report to PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select(
                DB::raw('DATE(orders.created_at) as order_date'),
                'order_items.menu_name',
                'order_items.price',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy(DB::raw('DATE(orders.created_at)'), 'order_items.menu_name', 'order_items.price')
            ->orderBy('order_date', 'desc')
            ->orderBy('total_qty', 'desc');

        // Filter Logika (Sama seperti index)
        if ($request->period == 'today') {
            $query->whereDate('orders.created_at', Carbon::today());
            $periodText = 'Hari Ini (' . Carbon::today()->format('d M Y') . ')';
        } elseif ($request->period == 'yesterday') {
            $query->whereDate('orders.created_at', Carbon::yesterday());
            $periodText = 'Kemarin (' . Carbon::yesterday()->format('d M Y') . ')';
        } elseif ($request->period == 'week') {
            $query->whereBetween('orders.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            $periodText = 'Minggu Ini';
        } elseif ($request->period == 'month') {
            $query->whereMonth('orders.created_at', Carbon::now()->month);
            $periodText = 'Bulan ' . Carbon::now()->format('F Y');
        } elseif ($request->period == 'custom' && $request->start_date && $request->end_date) {
            $query->whereBetween('orders.created_at', [$request->start_date, $request->end_date]);
            $periodText = 'Periode ' . Carbon::parse($request->start_date)->format('d/m/Y') . ' - ' . Carbon::parse($request->end_date)->format('d/m/Y');
        } else {
            $periodText = 'Semua Waktu';
        }

        $reportData = $query->get();

        // Hitung Total untuk Footer Tabel
        $totalQty = $reportData->sum('total_qty');
        $totalRevenue = $reportData->sum('total_revenue');

        $pdf = Pdf::loadView('admin.reports.pdf', compact('reportData', 'periodText', 'totalQty', 'totalRevenue'));
        
        // Set paper landscape agar muat banyak kolom
        $pdf->setPaper('a4', 'landscape'); 

        return $pdf->stream('laporan-penjualan.pdf');
    }

    /**
     * Get sales data for charts.
     */
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'week');
        
        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->subWeek();
                $endDate = Carbon::now();
                break;
            case 'month':
                $startDate = Carbon::now()->subMonth();
                $endDate = Carbon::now();
                break;
            case 'year':
                $startDate = Carbon::now()->subYear();
                $endDate = Carbon::now();
                break;
            default:
                $startDate = Carbon::now()->subWeek();
                $endDate = Carbon::now();
        }

        $dailyRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json($dailyRevenue);
    }
}
