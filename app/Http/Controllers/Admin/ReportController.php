<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the sales report.
     */
    public function index(Request $request): View
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

        return view('admin.reports.index', compact(
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
        ));
    }

    /**
     * Export sales report to PDF.
     */
    public function exportPdf(Request $request)
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

        $orders = Order::with(['table', 'orderItems.menu'])
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('payment_status', 'paid')
            ->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total');

        // Generate PDF (you can use packages like dompdf or snappy)
        // For now, we'll return a view that can be printed
        return view('admin.reports.pdf', compact(
            'orders',
            'totalOrders',
            'totalRevenue',
            'startDate',
            'endDate'
        ));
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
