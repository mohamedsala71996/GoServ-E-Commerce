<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'details',
        'category_id',
        'weight',
        'brand_id',
        // 'price',
        // 'size_id'
    ];

    public $translatable = ['name', 'description','details'];

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function productColors() //productColors
    {
        return $this->hasMany(ProductColor::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // public function offers()
    // {
    //     return $this->hasMany(Offer::class)->where('is_active', true) // Ensure the offer is active
    //     ->where('start_time', '<=', now()) // Offer has started
    //     ->where('end_time', '>=', now()) // Offer has not ended
    //     ->latest();
    // }

    // public function getPriceAfterDiscountAttribute()
    // {
    //     $offer = $this->offers()->first(); // Get the first active offer
    //     if ($offer) {
    //         return $this->price - ($this->price * ($offer->discount_percentage / 100));
    //     }
    //     return $this->$this->productColors[0]->productColorSizes[0]->price;
    // }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }
    // public function getSoldCountAttribute()
    // {
    //     return $this->sales()->sum('quantity');
    // }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('status', 'approved');
    }
    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating');
    }

    public function scopeSearch(Builder $query, $searchTerm, $locale = null)
    {
        $locale = $locale ?: app()->getLocale(); // Default to the current app locale

        return $query->where(function ($query) use ($searchTerm, $locale) {
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, '$.\"{$locale}\"')) LIKE ?", ["%{$searchTerm}%"])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(description, '$.\"{$locale}\"')) LIKE ?", ["%{$searchTerm}%"])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(details, '$.\"{$locale}\"')) LIKE ?", ["%{$searchTerm}%"]);
        });
    }

    public static function countProductsByRating()
    {
        $subquery = DB::table('product_reviews')
        ->select('product_id', DB::raw('FLOOR(AVG(rating)) AS average_rating'))
        ->where('status', 'approved')
        ->groupBy('product_id');

    // Main query to count products per floored average rating
    return DB::table(DB::raw("({$subquery->toSql()}) as sub"))
        ->mergeBindings($subquery) // Merge bindings from the subquery
        ->select('average_rating as rating', DB::raw('COUNT(*) AS count'))
        ->groupBy('average_rating')
        ->get();
    }
    // public function sales()
    // {
    //     return $this->hasMany(Sale::class);
    // }

}
