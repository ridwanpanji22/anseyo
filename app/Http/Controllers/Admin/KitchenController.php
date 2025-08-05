<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    /**
     * Display kitchen dashboard.
     */
    public function dashboard(): View
    {
        // Get orders that need kitchen attention
        $pendingOrders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $preparingOrders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'preparing')
            ->latest()
            ->get();

        $readyOrders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'ready')
            ->latest()
            ->limit(5)
            ->get();

        // Statistics
        $stats = [
            'pending' => $pendingOrders->count(),
            'preparing' => $preparingOrders->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'total_today' => Order::whereDate('created_at', today())->count(),
        ];

        return view('admin.kitchen.dashboard', compact('pendingOrders', 'preparingOrders', 'readyOrders', 'stats'));
    }

    /**
     * Update order status to preparing.
     */
    public function startPreparing(Order $order)
    {
        $order->update([
            'status' => 'preparing',
            'prepared_at' => now(),
        ]);

        // Update table status
        $this->updateTableStatus($order);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan #' . $order->order_number . ' sedang disiapkan',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Update order status to ready.
     */
    public function markReady(Order $order)
    {
        $order->update([
            'status' => 'ready',
            'prepared_at' => $order->prepared_at ?? now(),
        ]);

        // Update table status
        $this->updateTableStatus($order);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan #' . $order->order_number . ' siap disajikan',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Get orders by status for AJAX updates.
     */
    public function getOrdersByStatus(Request $request)
    {
        $status = $request->status;
        
        $orders = Order::with(['table', 'orderItems.menu'])
            ->where('status', $status)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'html' => view('admin.kitchen.partials.orders-list', compact('orders', 'status'))->render()
        ]);
    }

    /**
     * Update table status based on order status.
     */
    private function updateTableStatus(Order $order)
    {
        if (!$order->table) {
            return;
        }

        $table = $order->table;

        // Check if there are any active orders for this table
        $activeOrders = Order::where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
            ->count();

        if ($activeOrders > 0) {
            // Table is occupied
            $table->update(['status' => 'occupied']);
        } else {
            // No active orders, table is available
            $table->update(['status' => 'available']);
        }
    }
}
