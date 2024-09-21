<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ShortDataProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with(['category', 'reviews.user'])
        ->whereHas('productColors.productColorSizes.offers', function ($query) {
            $query->where('quantity', '!=', 0);
        })
        ->get();
        return ShortDataProductResource::collection($products);
    }
    public function newProducts()
    {
        $products = Product::with(['category', 'productColors.productColorSizes.offers', 'reviews.user'])
        ->whereHas('productColors.productColorSizes', function ($query) {
            $query->where('quantity', '!=', 0);
        })
        ->latest()->get();
        return ShortDataProductResource::collection($products);
    }

    public function productsWithOffers()
    {
        $products = Product::whereHas('productColors.productColorSizes.offers', function ($query) {
            $query->where('is_active', true)
                ->where('start_time', '<=', now())
                ->where('end_time', '>=', now());
        })->with(['category', 'productColors.productColorSizes.offers', 'reviews.user'])->get();

        return ShortDataProductResource::collection($products);
    }
    public function show($id)
    {
        try {
            // Attempt to find the product with the specified ID and include related data
            $product = Product::with([
                'category',
                'productColors.productColorSizes.offers',
                'reviews.user'
            ])->findOrFail($id);

            return new ProductResource($product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Product not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while retrieving the product'], 500);
        }
    }


    public function search(Request $request)
    {
        $searchTerm = $request->input('query', '');
        $locale = $request->input('lang', app()->getLocale()); // Get locale from request or default to app locale

        if (empty($searchTerm)) {
            return response()->json(['message' => 'Search term cannot be empty'], 400);
        }

        $products = Product::search($searchTerm, $locale)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        return ShortDataProductResource::collection($products);
    }


}
