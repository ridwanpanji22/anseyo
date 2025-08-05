<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WaiterController extends Controller
{
    /**
     * Display waiter dashboard.
     */
    public function dashboard(): View
    {
        // Get orders that need waiter attention
        $readyOrders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'ready')
            ->latest()
            ->get();

        $servedOrders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'served')
            ->latest()
            ->limit(5)
            ->get();

        $completedOrders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'completed')
            ->latest()
            ->limit(5)
            ->get();

        // Get tables status
        $tables = Table::with(['orders' => function($query) {
            $query->whereIn('status', ['pending', 'preparing', 'ready', 'served']);
        }])->get();

        // Statistics
        $stats = [
            'ready' => $readyOrders->count(),
            'served' => Order::where('status', 'served')->count(),
            'completed_today' => Order::where('status', 'completed')
                ->whereDate('created_at', today())
                ->count(),
            'occupied_tables' => $tables->where('status', 'occupied')->count(),
        ];

        return view('waiter.dashboard', compact('readyOrders', 'servedOrders', 'completedOrders', 'tables', 'stats'));
    }

    /**
     * Update order status to served.
     */
    public function markServed(Order $order)
    {
        $order->update([
            'status' => 'served',
            'served_at' => now(),
        ]);

        // Update table status
        $this->updateTableStatus($order);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan #' . $order->order_number . ' telah disajikan',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Update order status to completed.
     */
    public function markCompleted(Order $order)
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update table status
        $this->updateTableStatus($order);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan #' . $order->order_number . ' telah selesai',
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
            'html' => view('waiter.partials.orders-list', compact('orders', 'status'))->render()
        ]);
    }

    /**
     * Get tables status for AJAX updates.
     */
    public function getTablesStatus()
    {
        $tables = Table::with(['orders' => function($query) {
            $query->whereIn('status', ['pending', 'preparing', 'ready', 'served']);
        }])->get();

        return response()->json([
            'success' => true,
            'tables' => $tables,
            'html' => view('waiter.partials.tables-list', compact('tables'))->render()
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
