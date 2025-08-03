<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Tampilkan form pemesanan untuk customer
     */
    public function create($tableId)
    {
        $table = Table::where('id', $tableId)
            ->where('is_active', true)
            ->firstOrFail();
            
        $categories = \App\Models\Category::where('is_active', true)
            ->with(['menus' => function($query) {
                $query->where('is_available', true)->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
            
        return view('order.create', compact('table', 'categories'));
    }

    /**
     * Proses simpan pesanan customer
     */
    public function store(Request $request, $tableId)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'nullable|integer|min:0|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        // Filter hanya item yang quantity > 0
        $selectedItems = collect($request->items)->filter(function($item) {
            return isset($item['quantity']) && $item['quantity'] > 0;
        });

        if ($selectedItems->isEmpty()) {
            return back()->withErrors(['error' => 'Silakan pilih minimal satu menu untuk dipesan.']);
        }

        $table = Table::findOrFail($tableId);
        
        // Cek apakah ada pesanan yang sedang diproses di meja ini
        $activeOrder = Order::where('table_id', $table->id)
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->first();
            
        if ($activeOrder) {
            return back()->withErrors(['error' => 'Meja ini masih memiliki pesanan yang sedang diproses.']);
        }

        // Buat pesanan baru
        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'table_id' => $table->id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $request->notes,
            'ordered_at' => now(),
        ]);

        $totalItems = 0;
        $subtotal = 0;

        // Buat item pesanan hanya untuk yang dipilih
        foreach ($selectedItems as $item) {
            $menu = Menu::findOrFail($item['menu_id']);
            $itemSubtotal = $menu->price * $item['quantity'];
            
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $item['quantity'],
                'subtotal' => $itemSubtotal,
                'status' => 'pending',
            ]);
            
            $totalItems += $item['quantity'];
            $subtotal += $itemSubtotal;
        }

        // Update total pesanan
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;
        
        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        // Update table status to occupied
        $table->update(['status' => 'occupied']);

        return redirect()->route('order.show', $order->id)
            ->with('success', 'Pesanan berhasil dikirim! Pesanan Anda sedang diproses.');
    }

    /**
     * Tampilkan status pesanan
     */
    public function show($orderId)
    {
        $order = Order::with(['orderItems.menu', 'table'])
            ->findOrFail($orderId);
            
        return view('order.show', compact('order'));
    }

    /**
     * Generate nomor pesanan unik
     */
    private function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = Order::whereDate('created_at', today())->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
