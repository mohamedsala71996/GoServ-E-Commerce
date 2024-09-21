<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\ProductFeatureRequest;
use App\Http\Requests\Api\Dashboard\StoreProductFeatureRequest;
use App\Http\Resources\ProductFeatureResource;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductFeature;
use Illuminate\Http\Request;

class ProductFeatureController extends Controller
{
    // public function __construct()
    // {
    //     // Apply middleware to the constructor
    //     $this->middleware(function ($request, $next) {
    //         if ( !auth()->user() instanceof Admin) {
    //             return response()->json(['message' => 'You are not authenticated as an admin.'], 401);
    //         }
    //         return $next($request);
    //     });
    // }
    public function getProductFeatures($product_id)
    {
        $productFeatures = ProductFeature::where('product_id',$product_id)->get(); // Retrieve all product features

        return ProductFeatureResource::collection($productFeatures); // Return collection of resources
    }
    public function createProductFeatures(ProductFeatureRequest $request, $product_id)
    {
        // Validate the request data
        $validatedData = $request->validated();
       $product= Product::findOrFail($product_id);
      $productFeature= $product->features()->create($validatedData);

        // Return the created product feature
        return response()->json($productFeature, 201);
    }
    public function update(ProductFeatureRequest $request, $id)
    {
        $data = $request->validated();
        $productFeature = ProductFeature::findOrFail($id);
        $productFeature->update($data);

        return response()->json($productFeature);
    }
     public function destroy( $id)
    {
        ProductFeature::FindOrFail($id)->delete();

        return response()->json(['message' => 'Product feature deleted successfully'], 200); // Return with 200 OK status
    }
}
