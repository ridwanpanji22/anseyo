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
            ->whereIn('status', ['ready'])
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
            $query->whereIn('status', ['pending', 'preparing', 'ready']);
        }])->get();

        // Statistics
        $stats = [
            'unpaid' => $unpaidOrders->count(),
            'paid_today' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->count(),
            'total_revenue_today' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('total'),
            'occupied_tables' => $tables->where('status', 'occupied')->count(),
        ];

        return view('cashier.dashboard', compact('unpaidOrders', 'paidOrders', 'tables', 'stats'));
    }

    /**
     * Update payment status to paid.
     */
    public function markPaid(Request $request, Order $order)
    {
        $request->validate([
            'amount_received' => 'required|numeric|min:' . $order->total,
            'payment_method' => 'required|in:cash,card,qris,transfer',
        ]);

        $amountReceived = $request->amount_received;
        $changeAmount = $amountReceived - $order->total;

        $order->update([
            'payment_status' => 'paid',
            'status' => 'completed',
            'amount_paid' => $order->total,
            'remaining_amount' => 0,
            'amount_received' => $amountReceived,
            'change_amount' => $changeAmount,
            'payment_method' => $request->payment_method,
            'receipt_number' => $this->generateReceiptNumber(),
            'completed_at' => $order->completed_at ?? now(),
            'paid_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran lunas berhasil dicatat',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Generate receipt number.
     */
    private function generateReceiptNumber(): string
    {
        $prefix = 'RCP';
        $date = now()->format('Ymd');
        $lastReceipt = Order::whereDate('paid_at', today())->latest()->first();
        
        if ($lastReceipt && $lastReceipt->receipt_number) {
            $lastNumber = (int) substr($lastReceipt->receipt_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
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
                  ->whereIn('status', ['ready']);
        } elseif ($paymentStatus === 'paid') {
            $query->where('payment_status', $paymentStatus);
        } else {
            $query->where('payment_status', 'paid');
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
            $query->whereIn('status', ['pending', 'preparing', 'ready']);
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

    /**
     * Show order details for cashier.
     */
    public function showOrder(Order $order): View
    {
        return view('cashier.orders.show', compact('order'));
    }

    /**
     * Print receipt for paid order.
     */
    public function printReceipt(Order $order): View
    {
        if ($order->payment_status !== 'paid') {
            abort(404, 'Receipt hanya tersedia untuk pesanan yang sudah lunas');
        }
        
        return view('cashier.orders.receipt', compact('order'));
    }
}
