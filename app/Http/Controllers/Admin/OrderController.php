<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $orders = Order::with(['table', 'orderItems.menu'])
            ->latest()
            ->paginate(15);
            
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): View
    {
        $order->load(['table', 'orderItems.menu']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): View
    {
        $order->load(['table', 'orderItems.menu']);
        return view('admin.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
            'payment_status' => 'required|in:unpaid,paid,partial',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        
        // Update timestamps based on status
        switch ($request->status) {
            case 'preparing':
                $data['prepared_at'] = now();
                break;
            case 'ready':
                $data['prepared_at'] = $order->prepared_at ?? now();
                break;
            case 'served':
                $data['served_at'] = now();
                break;
            case 'completed':
                $data['completed_at'] = now();
                break;
        }

        $order->update($data);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Status pesanan berhasil diperbarui');
    }

    /**
     * Update order status via AJAX.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served,completed,cancelled',
        ]);

        $data = ['status' => $request->status];
        
        // Update timestamps based on status
        switch ($request->status) {
            case 'preparing':
                $data['prepared_at'] = now();
                break;
            case 'ready':
                $data['prepared_at'] = $order->prepared_at ?? now();
                break;
            case 'served':
                $data['served_at'] = now();
                break;
            case 'completed':
                $data['completed_at'] = now();
                break;
        }

        $order->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diperbarui',
            'order' => $order->fresh(['table', 'orderItems.menu'])
        ]);
    }

    /**
     * Update payment status.
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,paid,partial',
        ]);

        $order->update(['payment_status' => $request->payment_status]);

        return response()->json([
            'success' => true,
            'message' => 'Status pembayaran berhasil diperbarui',
            'order' => $order->fresh()
        ]);
    }

    /**
     * Get orders by status.
     */
    public function getByStatus($status)
    {
        $orders = Order::with(['table', 'orderItems.menu'])
            ->where('status', $status)
            ->latest()
            ->get();
            
        return view('admin.orders.by-status', compact('orders', 'status'));
    }

    /**
     * Get pending orders for kitchen.
     */
    public function kitchenOrders()
    {
        $orders = Order::with(['table', 'orderItems.menu'])
            ->whereIn('status', ['pending', 'preparing'])
            ->latest()
            ->get();
            
        return view('admin.orders.kitchen', compact('orders'));
    }

    /**
     * Get ready orders for waiters.
     */
    public function waiterOrders()
    {
        $orders = Order::with(['table', 'orderItems.menu'])
            ->where('status', 'ready')
            ->latest()
            ->get();
            
        return view('admin.orders.waiter', compact('orders'));
    }
}
