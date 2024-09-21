<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_color_size_id',
        'discount_percentage',
        'start_time',
        'end_time',
        'is_active',
        'global_offer_id'
    ];

    // Optionally, define relationships if needed
    public function productColorSize()
    {
        return $this->belongsTo(ProductColorSize::class);
    }


}
