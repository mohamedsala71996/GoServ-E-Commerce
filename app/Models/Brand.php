<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Brand extends Model
{
    use HasFactory,HasTranslations;
    protected $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
}
