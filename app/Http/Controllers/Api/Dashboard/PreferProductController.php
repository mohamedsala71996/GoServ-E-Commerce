<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StorePreferProductRequest;
use App\Http\Requests\Api\Dashboard\UpdatePreferProductRequest;
use App\Http\Resources\PreferProductResource;
use App\Models\Admin;
use App\Models\PreferProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PreferProductController extends Controller
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
        $preferProducts = PreferProduct::with('product')->take(3)->get();

        return PreferProductResource::collection($preferProducts);
    }
    public function store(StorePreferProductRequest $request)
    {
        $data = $request->validated();
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('prefer-products', 'public'); // Store photo in 'public/prefer-products'
            $data['photo'] = $photoPath;
        }
        $prefer_product = PreferProduct::create($data);
        return response()->json($prefer_product, 201);
    }

    public function updatePreferProduct(UpdatePreferProductRequest $request)
    {
        $prefer_product=PreferProduct::find($request->prefer_product_id);
        $validatedData = $request->validated();

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($prefer_product->photo ) {
                Storage::disk('public')->delete($prefer_product->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->store('prefer-products', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $prefer_product->update($validatedData);
        return response()->json($prefer_product, 201);
    }
    public function destroy($id)
    {
        $preferProduct = PreferProduct::findOrFail($id);

        // Delete the photo if it exists
        if ($preferProduct->photo ) {
            Storage::disk('public')->delete($preferProduct->photo);
        }

        $preferProduct->delete();
        return response()->json(['message' => 'Prefer product deleted successfully'], 200);
    }
}
