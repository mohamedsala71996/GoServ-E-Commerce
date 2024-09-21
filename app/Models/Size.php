<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['size', 'type'];
    public function productColorSizes()
    {
        return $this->hasMany(ProductColorSize::class, 'size_id');
    }

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductColorSize::class,
            'size_id', // Foreign key on product_color_size table
            'id', // Foreign key on product table
            'id', // Local key on sizes table
            'product_color_id' // Local key on product_color_size table
        );
    }
}
