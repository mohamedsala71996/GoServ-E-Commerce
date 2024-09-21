<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_weight',
        'max_weight',
        'additional_rate',
    ];

}
