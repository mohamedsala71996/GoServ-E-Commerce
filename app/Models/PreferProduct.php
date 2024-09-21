<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class PreferProduct extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'prefer_products';
    protected $fillable = ['title', 'product_id', 'photo'];
    protected $translatable = ['title'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
