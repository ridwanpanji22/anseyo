<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Table;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Update table status to occupied when order is created
        if ($order->table) {
            $order->table->update(['status' => 'occupied']);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Update table status based on order status
        if ($order->table) {
            $this->updateTableStatus($order->table);
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        // Update table status when order is deleted
        if ($order->table) {
            $this->updateTableStatus($order->table);
        }
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        // Update table status when order is restored
        if ($order->table) {
            $this->updateTableStatus($order->table);
        }
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        // Update table status when order is force deleted
        if ($order->table) {
            $this->updateTableStatus($order->table);
        }
    }

    /**
     * Update table status based on active orders.
     */
    private function updateTableStatus(Table $table): void
    {
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
