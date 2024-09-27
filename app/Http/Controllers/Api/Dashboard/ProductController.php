<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreCategoryRequest;
use App\Http\Requests\Api\Dashboard\StoreProductRequest;
use App\Http\Requests\Api\Dashboard\UpdateProductRequest;
use App\Http\Resources\ProductDashboardResource;
use App\Http\Resources\ShortDataProductResource;
use App\Mail\ProductAvailable;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use App\Models\ProductNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
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

    public function index()
    {
        $products = Product::orderBy('category_id')->get();
        $productsResource = ProductDashboardResource::collection($products);

        // Return the collection with a status code (200 OK is the default)
        return response()->json($productsResource, 200);
    }


    public function show($id)
    {
        try {
            // Find the product by ID
            $product = Product::with('productColors.productColorSizes')->findOrFail($id);

            // Return the product with its related colors and sizes
            return response()->json(new ProductDashboardResource($product), 200);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            // Return a 404 Not Found response if the product is not found
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
    public function store(StoreProductRequest $request)
    {

        $data = $request->validated();

        $product = Product::create($data);

        // Handle product colors if provided
        if ($request->has('colors')) {
            $colors = $request->colors;
            $photoPaths = [];
            foreach ($colors as $color) {
                // Ensure 'photos' key exists and handle each file
                foreach ($color['photos'] as $photo) {
                    // Assuming $photo is an instance of UploadedFile
                    $photoPath = $photo->store('product-colors', 'public');
                    $photoPaths[] = $photoPath;
                }
                // Store color photos as JSON
                $productColor =  $product->productColors()->create([
                    'color_id' => $color['color_id'] ?? null,
                    'photos' => json_encode($photoPaths), // Store color photos as JSON
                    // 'price' => $color['price'] ?? 0,
                    // 'quantity' => $color['quantity'] ?? 0,
                ]);

                if (isset($color['sizes'])) {
                    foreach ($color['sizes'] as $size) {
                        $productColor->productColorSizes()->create([
                            'size_id' => $size['size_id'] ?? null,
                            'quantity' => $size['quantity'] ?? 1,
                            'price' => $size['price'],
                            'cost' => $size['cost'],
                        ]);
                    }
                }
            }
        }
        return response()->json(new ProductDashboardResource($product->load('productColors.productColorSizes')), 201);
    }


    public function updateProduct(UpdateProductRequest $request, $id)
    {
        $data = $request->validated();
        $product = Product::findOrFail($id);

        $product->update($data);

        // Handle product colors update
        if (isset($data['colors']) && is_array($data['colors'])) {
            foreach ($data['colors'] as $index => $color) {
                $colorData = $color;
                if (isset($color['id'])) {
                    // Update existing product color
                    $productColor = ProductColor::findOrFail($color['id']);
                    $photoPaths = [];

                    // Handle color photo update
                    if (isset($color['photos']) && $request->hasFile('colors.' . $index . '.photos')) {
                        if ($productColor->photos) {
                            foreach (json_decode($productColor->photos, true) as $existingPhoto) {
                                Storage::disk('public')->delete($existingPhoto);
                            }
                        }
                        foreach ($color['photos'] as $photo) {
                            $photoPath = $photo->store('product-colors', 'public');
                            $photoPaths[] = $photoPath;
                        }
                        $colorData['photos'] = json_encode($photoPaths);
                    }

                    $productColor->update($colorData);

                    // Handle product color sizes update
                    if (isset($color['sizes'])) {
                        foreach ($color['sizes'] as $size) {
                            if (isset($size['id'])) {
                                // Update existing size
                                $productColorSize = ProductColorSize::findOrFail($size['id']);
                               $notifies= ProductNotification::where('notified', 0)->get();
                                    foreach ($notifies as $notify) {
                                        if ($notify->product_color_size_id == $productColorSize->id && $productColorSize->quantity == 0 ) {
                                            Mail::to($notify->email)->send(new ProductAvailable($productColorSize->productColor->product));
                                            $notify->notified = 1;
                                            $notify->save();
                                        }
                                    }
                                    $productColorSize->update($size);
                            } else {
                                // Create new size
                                $productColor->productColorSizes()->create($size);
                            }
                        }
                    }
                } else {
                    // Create new product color
                    if (isset($color['photos']) && $request->hasFile('colors.' . $index . '.photos')) {
                        foreach ($color['photos'] as $photo) {
                            $photoPath = $photo->store('product-colors', 'public');
                            $photoPaths[] = $photoPath;
                        }
                        $colorData['photos'] = json_encode($photoPaths);
                    }
                    $productColor = $product->productColors()->create($colorData);

                    // Create new sizes for the new product color
                    if (isset($color['sizes'])) {
                        foreach ($color['sizes'] as $size) {
                            $productColor->productColorSizes()->create($size);
                        }
                    }
                }
            }
        }

        // Remove product colors if specified
        if (isset($data['remove_colors']) && is_array($data['remove_colors'])) {
            foreach ($data['remove_colors'] as $colorId) {
                $productColor = $product->productColors()->find($colorId);
                if ($productColor) {
                    if ($productColor->photos) {
                        foreach (json_decode($productColor->photos, true) as $photo) {
                            Storage::disk('public')->delete($photo);
                        }
                    }
                    $productColor->delete();
                }
            }
        }

        return response()->json(new ProductDashboardResource($product->load('productColors.productColorSizes')), 200);
    }



    public function destroy($id)
    {
        try {
            // Find the product by ID
            $product = Product::findOrFail($id);

            // Loop through each product color and delete associated photos and sizes
            foreach ($product->productColors as $color) {
                // Decode the stored JSON of photos
                $photos = json_decode($color->photos, true);
                if (is_array($photos)) {
                    foreach ($photos as $photo) {
                        // Delete each photo from storage
                        Storage::disk('public')->delete($photo);
                    }
                }

                // Loop through each color's sizes and delete them
                foreach ($color->productColorSizes as $size) {
                    $size->delete(); // Delete each size record
                }

                // Delete the product color record
                $color->delete();
            }

            // Delete the product itself
            $product->delete();

            return response()->json(['message' => 'Product deleted successfully'], 200);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            // Return a 500 Internal Server Error if something goes wrong
            return response()->json(['error' => 'An error occurred while deleting the product'], 500);
        }
    }


    public function getSizesOrderedByType()
    {
        $sizes = DB::table('sizes')
            ->orderBy('type')
            // ->orderBy('size') // Optional: also order by size within each type
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $sizes
        ], 200);
    }



}
