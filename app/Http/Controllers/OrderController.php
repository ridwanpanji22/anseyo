<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Show the order form.
     */
    public function create(Request $request): View
    {
        $tableNumber = $request->get('table');
        $table = Table::where('number', $tableNumber)->first();
        
        if (!$table) {
            abort(404, 'Meja tidak ditemukan');
        }

        $categories = \App\Models\Category::active()
            ->with(['menus' => function($query) {
                $query->available()->ordered();
            }])
            ->ordered()
            ->get();

        return view('order.create', compact('table', 'categories'));
    }

    /**
     * Store the order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'table_id' => 'required|exists:tables,id',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1|max:10',
            'items.*.notes' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for over order (more than 5 items)
        $totalItems = collect($request->items)->sum('quantity');
        if ($totalItems > 5) {
            return back()->withErrors(['over_order' => 'Pesanan tidak boleh lebih dari 5 item. Total item Anda: ' . $totalItems]);
        }

        // Create order
        $order = Order::create([
            'order_number' => Order::generateOrderNumber(),
            'table_id' => $request->table_id,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'notes' => $request->notes,
            'ordered_at' => now(),
        ]);

        // Create order items
        foreach ($request->items as $item) {
            $menu = Menu::find($item['menu_id']);
            
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $menu->id,
                'menu_name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $item['quantity'],
                'subtotal' => $menu->price * $item['quantity'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        // Calculate totals
        $order->calculateTotals();

        // Update table status
        $table = Table::find($request->table_id);
        $table->update(['status' => 'occupied']);

        return redirect()->route('order.show', $order->id)
            ->with('success', 'Pesanan berhasil dikirim ke dapur!');
    }

    /**
     * Show the order details.
     */
    public function show(Order $order): View
    {
        $order->load(['table', 'orderItems.menu']);
        return view('order.show', compact('order'));
    }

    /**
     * Get menu items for AJAX request.
     */
    public function getMenuItems(Request $request)
    {
        $categoryId = $request->get('category_id');
        
        $menus = Menu::available()
            ->when($categoryId, function($query) use ($categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->with('category')
            ->ordered()
            ->get();

        return response()->json($menus);
    }

    /**
     * Check table availability.
     */
    public function checkTable(Request $request)
    {
        $tableNumber = $request->get('table');
        $table = Table::where('number', $tableNumber)->first();

        if (!$table) {
            return response()->json(['available' => false, 'message' => 'Meja tidak ditemukan']);
        }

        if (!$table->is_active) {
            return response()->json(['available' => false, 'message' => 'Meja sedang tidak tersedia']);
        }

        if ($table->status !== 'available') {
            return response()->json(['available' => false, 'message' => 'Meja sedang digunakan']);
        }

        return response()->json(['available' => true, 'table' => $table]);
    }
}
