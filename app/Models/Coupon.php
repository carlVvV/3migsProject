<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'description',
        'max_usage',
        'used_count',
        'min_order_amount',
        'expiry_date',
        'status'
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2'
    ];

    /**
     * Get the orders that used this coupon
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the coupon is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        
        return $this->expiry_date->isPast();
    }

    /**
     * Check if the coupon can be used
     */
    public function canBeUsed(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        if ($this->isExpired()) {
            return false;
        }
        
        if ($this->max_usage && $this->used_count >= $this->max_usage) {
            return false;
        }
        
        return true;
    }

    /**
     * Check if the coupon can be applied to an order amount
     */
    public function canBeAppliedToOrder(float $orderAmount): bool
    {
        if (!$this->canBeUsed()) {
            return false;
        }
        
        if ($this->min_order_amount && $orderAmount < $this->min_order_amount) {
            return false;
        }
        
        return true;
    }

    /**
     * Calculate discount amount for an order
     */
    public function calculateDiscount(float $orderAmount): float
    {
        if (!$this->canBeAppliedToOrder($orderAmount)) {
            return 0;
        }
        
        switch ($this->type) {
            case 'percentage':
                return ($orderAmount * $this->value) / 100;
            case 'fixed':
                return min($this->value, $orderAmount);
            case 'free-shipping':
                // This would typically be handled in shipping calculation
                return 0;
            default:
                return 0;
        }
    }

    /**
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Get formatted value display
     */
    public function getFormattedValueAttribute(): string
    {
        switch ($this->type) {
            case 'percentage':
                return $this->value . '%';
            case 'fixed':
                return '₱' . number_format($this->value, 2);
            case 'free-shipping':
                return 'Free Shipping';
            default:
                return '';
        }
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        switch ($this->status) {
            case 'active':
                return 'bg-green-100 text-green-800';
            case 'inactive':
                return 'bg-gray-100 text-gray-800';
            case 'expired':
                return 'bg-red-100 text-red-800';
            default:
                return 'bg-gray-100 text-gray-800';
        }
    }

    /**
     * Scope for active coupons
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function($q) {
                        $q->whereNull('expiry_date')
                          ->orWhere('expiry_date', '>', now());
                    });
    }

    /**
     * Scope for expired coupons
     */
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', 'expired')
              ->orWhere('expiry_date', '<=', now());
        });
    }
}
