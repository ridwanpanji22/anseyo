<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_id',
        'menu_name',
        'price',
        'quantity',
        'subtotal',
        'notes',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the order that owns the order item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the menu that owns the order item.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Calculate subtotal for the order item.
     */
    public function calculateSubtotal(): void
    {
        $this->subtotal = $this->price * $this->quantity;
        $this->save();
    }

    /**
     * Scope a query to only include pending items.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include preparing items.
     */
    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    /**
     * Scope a query to only include ready items.
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }
}
