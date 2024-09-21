<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    protected $table = 'product_colors';

    protected $fillable = ['product_id', 'color_id', 'photos','quantity'];

    public function color()
    {
        return $this->belongsTo(Color::class,'color_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }



    public function productColorSizes() //productColors
    {
        return $this->hasMany(ProductColorSize::class);
    }
    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }
    // public function getSoldCountAttribute()
    // {
    //     return $this->sales()->sum('quantity');
    // }
}
