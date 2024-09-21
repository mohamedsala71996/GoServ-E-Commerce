<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Color extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'hex_code'];
    protected $translatable  = ['name'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function productColors()
    {
        return $this->hasMany(ProductColor::class);
    }

}
