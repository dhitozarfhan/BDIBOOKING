<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Invoice extends Model
{
    protected $fillable = [
        'booking_id',
        'billing_code',
        'invoice_file',
        'amount',
        'status',
        'issued_at',
        'due_date',
        'payment_proof',
        'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'issued_at' => 'datetime',
        'due_date' => 'datetime',
        'verified_at' => 'datetime',
    ];

    /**
     * Check if the invoice is effectively expired (real-time).
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->status === 'unpaid' && $this->due_date && $this->due_date->isPast();
    }

    /**
     * Get the effective status (accounts for real-time expiration).
     */
    public function getEffectiveStatusAttribute(): string
    {
        if ($this->is_expired) {
            return 'expired';
        }

        return $this->status;
    }

    /**
     * Scope for unpaid invoices past their due date.
     */
    public function scopeExpirable(Builder $query): Builder
    {
        return $query->where('status', 'unpaid')
            ->where('due_date', '<', now());
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
