<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone_number',
        'country',
        'city_id',
        'state',
        'address',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
