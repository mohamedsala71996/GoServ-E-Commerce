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
        'usage_user_limit',
        'used_count',
        'status'
    ];

    // Check if the coupon is valid based on date and usage
    public function isValid()
    {
        $now = now();

        return $this->valid_from <= $now &&
               $this->valid_until >= $now &&
               $this->status == 'active' &&
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



    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('times_used')->withTimestamps();
    }

    // Check if a user has reached their limit
    public function hasReachedUserLimit($user)
    {
        $userCoupon = $this->users()->where('user_id', $user->id)->first();

        if ($userCoupon && $userCoupon->pivot->times_used >= $this->usage_user_limit) {
            return true;
        }

        return false;
    }

    // Increment the user's coupon usage
    public function incrementUserUsage($user)
    {
        $userCoupon = $this->users()->where('user_id', $user->id)->first();

        if ($userCoupon) {
            $this->users()->updateExistingPivot($user->id, [
                'times_used' => $userCoupon->pivot->times_used + 1
            ]);
        } else {
            $this->users()->attach($user->id, ['times_used' => 1]);
        }
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
