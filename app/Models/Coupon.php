<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'discount_type',
        'discount_value',
        'max_discount_value',
        'max_redemptions',
        'redemptions',
        'minimum_cart_total',
        'is_stackable',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_discount_value' => 'decimal:2',
        'minimum_cart_total' => 'decimal:2',
        'is_stackable' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function isCurrentlyActive(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $now = Carbon::now();

        if ($this->starts_at && $this->starts_at->isFuture()) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_redemptions && $this->redemptions >= $this->max_redemptions) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (! $this->isCurrentlyActive()) {
            return 0.0;
        }

        if ($this->minimum_cart_total && $subtotal < $this->minimum_cart_total) {
            return 0.0;
        }

        $discount = $this->discount_type === 'percentage'
            ? $subtotal * ($this->discount_value / 100)
            : $this->discount_value;

        if ($this->max_discount_value) {
            $discount = min($discount, $this->max_discount_value);
        }

        return round($discount, 2);
    }
}
