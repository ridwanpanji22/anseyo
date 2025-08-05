<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    /**
     * Display cashier dashboard.
     */
    public function dashboard(): View
    {
        // Get orders that need payment attention
        $unpaidOrders = Order::with(['table', 'orderItems.menu'])
            ->where('payment_status', 'unpaid')
            ->whereIn('status', ['served', 'completed'])
            ->latest()
            ->get();

        $partialOrders = Order::with(['table', 'orderItems.menu'])
            ->where('payment_status', 'partial')
            ->latest()
            ->get();

        $paidOrders = Order::with(['table', 'orderItems.menu'])
            ->where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->latest()
            ->limit(10)
            ->get();

        // Get tables status
        $tables = Table::with(['orders' => function($query) {
            $query->whereIn('status', ['pending', 'preparing', 'ready', 'served']);
        }])->get();

        // Statistics
        $stats = [
            'unpaid' => $unpaidOrders->count(),
            'partial' => $partialOrders->count(),
            'paid_today' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->count(),
            'total_revenue_today' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('total'),
            'occupied_tables' => $tables->where('status', 'occupied')->count(),
        ];

        return view('cashier.dashboard', compact('unpaidOrders', 'partialOrders', 'paidOrders', 'tables', 'stats'));
    }

    /**
     * Update payment status to partial.
     */
    public function markPartialPayment(Order $order, Request $request)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0|max:' . $order->total,
        ]);

        $amountPaid = $request->amount_paid;
        $remainingAmount = $order->total - $amountPaid;

        $order->update([
            'payment_status' => 'partial',
            'amount_paid' => $amountPaid,
            'remaining_amount' => $remainingAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran sebagian berhasil dicatat',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Update payment status to paid.
     */
    public function markPaid(Order $order)
    {
        $order->update([
            'payment_status' => 'paid',
            'amount_paid' => $order->total,
            'remaining_amount' => 0,
            'paid_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran lunas berhasil dicatat',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Get orders by payment status for AJAX updates.
     */
    public function getOrdersByPaymentStatus(Request $request)
    {
        $paymentStatus = $request->payment_status;
        
        $query = Order::with(['table', 'orderItems.menu']);
        
        if ($paymentStatus === 'unpaid') {
            $query->where('payment_status', 'unpaid')
                  ->whereIn('status', ['served', 'completed']);
        } else {
            $query->where('payment_status', $paymentStatus);
        }
        
        $orders = $query->latest()->get();

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'html' => view('cashier.partials.orders-list', compact('orders', 'paymentStatus'))->render()
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
            'html' => view('cashier.partials.tables-list', compact('tables'))->render()
        ]);
    }

    /**
     * Get daily revenue report.
     */
    public function getDailyRevenue(Request $request)
    {
        $date = $request->date ?? today();
        
        $revenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', $date)
            ->sum('total');
            
        $orderCount = Order::where('payment_status', 'paid')
            ->whereDate('created_at', $date)
            ->count();

        return response()->json([
            'success' => true,
            'revenue' => $revenue,
            'order_count' => $orderCount,
            'date' => $date
        ]);
    }
}
