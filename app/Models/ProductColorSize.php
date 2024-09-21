<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    use HasFactory;

    protected $table = 'product_color_sizes';

    protected $fillable = ['product_color_id', 'size_id', 'quantity', 'price','cost'];

    /**
     * Get the product color that owns the size.
     */
    public function productColor()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }

    /**
     * Get the size associated with the product color.
     */
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }



    public function offers()
    {
        return $this->hasMany(Offer::class)->where('is_active', true) // Ensure the offer is active
        ->where('start_time', '<=', now()) // Offer has started
        ->where('end_time', '>=', now()) // Offer has not ended
        ->latest();
    }

    public function getPriceAfterDiscountAttribute()
    {
        $offer = $this->offers()->first(); // Get the first active offer
        if ($offer) {
            return $this->price - ($this->price * ($offer->discount_percentage / 100));
        }
        return $this->price;
    }


    public static function getOutOfStock()
    {
        return self::where('quantity', 0)->get();
    }
}
