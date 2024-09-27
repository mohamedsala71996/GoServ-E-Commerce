<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quantity',
        'product_color_size_id',
        'reminder'
    ];

    /**
     * Get the user that owns the cart item.
     */
    public function user()

    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function productColorSize()
    {
        return $this->belongsTo(ProductColorSize::class,'product_color_size_id');
    }

}
