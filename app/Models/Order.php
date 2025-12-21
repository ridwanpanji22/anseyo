<?php

namespace App\Models;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'table_id',
        'customer_name',
        'customer_phone',
        'status',
        'payment_status',
        'subtotal',
        'tax',
        'discount_amount',
        'discount_id',
        'total',
        'amount_paid',
        'remaining_amount',
        'amount_received',
        'change_amount',
        'payment_method',
        'receipt_number',
        'notes',
        'cancelled_reason',
        'cancelled_by',
        'ordered_at',
        'prepared_at',
        'served_at',
        'completed_at',
        'paid_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
        'prepared_at' => 'datetime',
        'served_at' => 'datetime',
        'completed_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Get the table that owns the order.
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Generate order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest()->first();
        
        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calculate order totals.
     */
    public function calculateTotals(): void
    {
        $this->loadMissing('orderItems');

        $subtotal = $this->orderItems->sum('subtotal');
        
        // Calculate Discount
        $discountAmount = 0;
        $discountId = null;
        
        // Find applicable discount
        $activeDiscount = \App\Models\Discount::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('min_purchase', '<=', $subtotal)
            ->orderBy('value', 'desc') // Apply the highest discount if multiple exist
            ->first();
            
        if ($activeDiscount) {
            $discountAmount = $subtotal * ($activeDiscount->value / 100);
            $discountId = $activeDiscount->id;
        }

        $taxRate = (float) Setting::get('tax_rate', 10);
        
        // Tax calculation: (Subtotal - Discount) * TaxRate
        $taxableAmount = max(0, $subtotal - $discountAmount);
        $tax = $taxableAmount * ($taxRate / 100);
        
        $total = $taxableAmount + $tax;
        
        $this->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'discount_id' => $discountId,
            'tax' => $tax,
            'total' => $total,
        ]);
    }

    /**
     * Scope a query to only include pending orders.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include preparing orders.
     */
    public function scopePreparing($query)
    {
        return $query->where('status', 'preparing');
    }

    /**
     * Scope a query to only include ready orders.
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }
}
