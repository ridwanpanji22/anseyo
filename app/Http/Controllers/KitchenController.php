<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KitchenController extends Controller
{
    /**
     * Display kitchen dashboard.
     */
    public function dashboard(): View
    {
        // Get orders by status
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
            ->get();

        // Statistics
        $stats = [
            'pending' => $pendingOrders->count(),
            'preparing' => $preparingOrders->count(),
            'ready' => $readyOrders->count(),
            'total_today' => Order::whereDate('created_at', today())->count(),
        ];

        return view('kitchen.dashboard', compact('pendingOrders', 'preparingOrders', 'readyOrders', 'stats'));
    }

    /**
     * Update order status to preparing.
     */
    public function startPreparing(Order $order)
    {
        try {
            $order->update([
                'status' => 'preparing',
                'preparing_at' => now(),
            ]);

            // Update table status
            $this->updateTableStatus($order);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan #' . $order->order_number . ' sedang disiapkan',
                'order' => $order->fresh(['table', 'orderItems.menu'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status to ready.
     */
    public function markReady(Order $order)
    {
        try {
            $order->update([
                'status' => 'ready',
                'ready_at' => now(),
            ]);

            // Update table status
            $this->updateTableStatus($order);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan #' . $order->order_number . ' siap disajikan',
                'order' => $order->fresh(['table', 'orderItems.menu'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate status pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel order from kitchen side when needed.
     */
    public function cancel(Order $order, Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        if (!in_array($order->status, ['pending', 'preparing'])) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan pada status saat ini.'
            ], 422);
        }

        $order->update([
            'status' => 'cancelled',
            'payment_status' => 'unpaid',
            'cancelled_reason' => $request->reason,
            'cancelled_at' => now(),
            'cancelled_by' => auth()->id(),
        ]);

        $this->updateTableStatus($order);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan #' . $order->order_number . ' dibatalkan.',
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
            'html' => view('kitchen.partials.orders-list', compact('orders', 'status'))->render()
        ]);
    }

    /**
     * Show order details.
     */
    public function showOrder(Order $order): View
    {
        $order->load(['table', 'orderItems.menu']);
        
        return view('kitchen.orders.show', compact('order'));
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
            ->whereIn('status', ['pending', 'preparing', 'ready'])
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
