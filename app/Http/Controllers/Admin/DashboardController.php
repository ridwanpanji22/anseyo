<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        // Get statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::pending()->count();
        $preparingOrders = Order::preparing()->count();
        $readyOrders = Order::ready()->count();
        
        $totalMenus = Menu::count();
        $availableMenus = Menu::available()->count();
        $totalCategories = Category::active()->count();
        
        $totalTables = Table::active()->count();
        $availableTables = Table::available()->count();
        
        // Get today's orders
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total');
        
        // Get recent orders
        $recentOrders = Order::with(['table', 'orderItems'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get top selling menus
        $topMenus = Menu::withCount(['orderItems'])
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'totalMenus',
            'availableMenus',
            'totalCategories',
            'totalTables',
            'availableTables',
            'todayOrders',
            'todayRevenue',
            'recentOrders',
            'topMenus'
        ));
    }
}
