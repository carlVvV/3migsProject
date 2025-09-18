<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_reference',
        'payment_method',
        'amount',
        'status',
        'currency',
        'payment_details',
        'transaction_id',
        'paid_at',
        'failed_at',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_details' => 'array',
        'paid_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    /**
     * Get the order that owns the payment.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the GCash payment ID if payment method is GCash.
     */
    public function getGcashPaymentIdAttribute(): ?string
    {
        if ($this->payment_method === 'gcash' && $this->payment_details) {
            return $this->payment_details['gcash_payment_id'] ?? 
                   $this->payment_details['reference_number'] ?? 
                   $this->transaction_id;
        }
        return null;
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment failed.
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
