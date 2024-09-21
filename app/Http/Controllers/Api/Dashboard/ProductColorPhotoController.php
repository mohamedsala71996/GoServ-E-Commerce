<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreProductColorPhotoRequest;
use App\Http\Requests\Api\Dashboard\UpdateProductColorPhotoRequest;
use App\Http\Resources\ProductColorPhotoResource;
use App\Models\Admin;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductColorPhotoController extends Controller
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
    // public function getColorPhotos($product_id)
    // {
    //     $colorPhotos = ProductColorPhoto::where('product_id',$product_id)->get();

    //     return ProductColorPhotoResource::collection($colorPhotos); // Return collection of resources
    // }

    public function createColorPhotos(StoreProductColorPhotoRequest $request, $product_id)
    {
        $data = $request->validated();
        $product= Product::findOrFail($product_id);

        // Handle file uploads if present
        if ($request->hasFile('photos')) {
            $photos = $request->file('photos');
            $photoPaths = [];

            foreach ($photos as $photo) {
                $photoPath = $photo->store('product_colors', 'public'); // Store photo in 'public/product_colors'
                $photoPaths[] = $photoPath;
            }

            $data['photos'] = json_encode($photoPaths); // Store photo paths as JSON
        }

        // $productColorPhoto = ProductColorPhoto::create($data);
        $productColorPhoto= $product->productColor()->create($data);

        return response()->json($productColorPhoto);
    }

    public function updateColorPhotos(Request $request){
        $validatedData = $request->validate([
            'product_color_id' => 'required|exists:product_colors,id',
            'color_id' => 'nullable|exists:colors,id',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|max:2048',
            'quantity' => 'nullable|integer|min:0',
        ]);

        // Create the product
        $productColor = ProductColor::find($request->product_color_id);
        if ($request->hasFile('photos')) {
            // Delete old photos if they exist
            if ($productColor->photos) {
                $oldPhotos = json_decode($productColor->photos, true);
                foreach ($oldPhotos as $oldPhoto) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
            $photos = $request->file('photos');
            $photoPaths = [];
            foreach ($photos as $photo) {
                $photoPath = $photo->store('product_colors', 'public');
                $photoPaths[] = $photoPath;
            }
            $validatedData['photos'] = json_encode($photoPaths); // Store photo paths as JSON
        }
        $productColor->update($validatedData);
        return response()->json($productColor);

    }
    public function destroy($id)
    {
        // Find the ProductColorPhoto by ID
        $productColorPhoto = ProductColor::findOrFail($id);

        // Delete associated photos if they exist
        if ($productColorPhoto->photos) {
            $oldPhotos = json_decode($productColorPhoto->photos, true);
            foreach ($oldPhotos as $oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        // Delete the ProductColorPhoto record
        $productColorPhoto->delete();

        return response()->json([
            'message' => 'Product color and photos deleted successfully.'
        ], 200);
    }
}
