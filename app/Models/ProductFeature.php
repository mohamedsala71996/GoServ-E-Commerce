<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ProductFeature extends Model
{
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'product_id',
    ];

    /**
     * Get the product that owns the feature.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
