<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_amount',
        'discount_percentage',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
    ];

    // Check if the coupon is valid based on date and usage
    public function isValid()
    {
        $now = now();

        return $this->valid_from <= $now &&
               $this->valid_until >= $now &&
               ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }

    // Apply discount to a given price
    public function applyDiscount($totalAmount)
    {
        if ($this->discount_amount) {
            return max($totalAmount - $this->discount_amount, 0);
        }

        if ($this->discount_percentage) {
            return max($totalAmount * ((100 - $this->discount_percentage) / 100), 0);
        }

        return $totalAmount;
    }

    // Increment used count
    public function incrementUsage()
    {
        $this->used_count++;
        $this->save();
    }
}
