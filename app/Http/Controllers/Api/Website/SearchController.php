<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllBrandsResource;
use App\Http\Resources\AllCategoriesResource;
use App\Http\Resources\AllColorResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShortDataProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{

    public function allCategories()
    {
        // Fetch categories with the count of products
        $categories = Category::withCount('products')->get();

        // Return as JSON response using CategoryResource
        return AllCategoriesResource::collection($categories);
    }
    public function allBrands()
    {
        // Fetch brands with the count of products
        $brands = Brand::withCount('products')->get();

        // Return as JSON response using BrandResource
        return AllBrandsResource::collection($brands);
    }
    public function allColors()
    {
        // Fetch colors with the count of associated product colors
        $colors = Color::withCount('productColors')->get();

        // Return as JSON response using ColorResource
        return AllColorResource::collection($colors);
    }

    public function getProductsCountByRating()
    {
        $ratings = Product::countProductsByRating();

        return response()->json($ratings);
    }



    public function searchProducts(Request $request)
    {
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'color_id' => 'nullable|exists:colors,id',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        // Start building the query
        $query = Product::query();

        // Apply filters if provided
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('color_id')) {
            $query->whereHas('productColor', function ($q) use ($request) {
                $q->where('color_id', $request->input('color_id'));
            });
        }

        if ($request->filled('rating')) {
            $rating = $request->input('rating');
            $query->whereHas('reviews', function ($q) use ($rating) {
                $q->select(DB::raw('product_id, FLOOR(AVG(rating)) as avg_rating'))
                  ->groupBy('product_id')
                  ->having(DB::raw('AVG(rating)'), '=', $rating);
            });
        }

        // Execute the query and get the results
        $products = $query->get();

        // Return as JSON response using ProductResource
        return ShortDataProductResource::collection($products);
    }
}
