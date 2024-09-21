<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_amount',
        'total_weight',
        'status',
        'tracking_number',
        'coupon_discount',
        'shipping_amount',
        'total_cost',
        'transaction_id',
        'source_data_sub_type'
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userDetail()
    {
        return $this->hasOne(userDetail::class,'order_id');
    }


}
