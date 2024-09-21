<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreBrandRequest;
use App\Http\Requests\Api\Dashboard\StoreServeralBrandsRequest;
use App\Http\Requests\Api\Dashboard\UpdateBrandRequest;
use App\Http\Requests\Api\Dashboard\UpdateSeveralBrandsRequest;
use App\Http\Resources\BrandResource;
use App\Models\Admin;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function __construct()
    {
        // Apply middleware to the constructor
        // $this->middleware(function ($request, $next) {
        //     if ( !auth()->user() instanceof Admin) {
        //         return response()->json(['message' => 'You are not authenticated as an admin.'], 401);
        //     }
        //     return $next($request);
        // });
    }
    public function index()
    {
        $brands = Brand::all();
        return BrandResource::collection($brands);
    }
    public function store(StoreBrandRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoPath = $logo->store('brands', 'public');
            $validatedData['logo'] = $logoPath;
        }

        $brand = Brand::create($validatedData);
        return response()->json($brand, 201);
    }

    public function updateBrand(UpdateBrandRequest $request)
    {
        $brand = Brand::find($request->brand_id);
        $validatedData = $request->validated();
        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }
            $logo = $request->file('logo');
            $logoPath = $logo->store('brands', 'public');
            $validatedData['logo'] = $logoPath;
        }
        $brand->update($validatedData);
        return new BrandResource($brand);
    }
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        if ($brand->logo) {
            Storage::disk('public')->delete($brand->logo);
        }
        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully'], 200);
    }

    public function storeSeveralBrands(StoreServeralBrandsRequest $request)
    {
        $validatedData = $request->validated();

        $brandsData = $validatedData['brands'];

        $createdBrands = [];

        foreach ($brandsData as $brandData) {
            if (isset($brandData['logo'])) {
                // Handle logo upload
                $logo = $brandData['logo'];
                $logoPath = $logo->store('brands', 'public');
                $brandData['logo'] = $logoPath;
            }

            $createdBrands[] = Brand::create($brandData);
        }

        return response()->json($createdBrands, 201);
    }

    public function updateOrCreateSeveralBrands(UpdateSeveralBrandsRequest $request)
    {
        $validatedData = $request->validated();
        $brandsData = $validatedData['brands'];

        $processedBrands = [];

        foreach ($brandsData as $brandData) {
            // Check if brand ID is provided
            if (isset($brandData['id'])) {
                // Update existing brand
                $brand = Brand::findOrFail($brandData['id']);

                // Check if a new logo is uploaded
                if (isset($brandData['logo'])) {
                    // Delete the old logo if it exists
                    if ($brand->logo) {
                        Storage::disk('public')->delete($brand->logo);
                    }

                    // Store the new logo
                    $logoPath = $brandData['logo']->store('brands', 'public');
                    $brandData['logo'] = $logoPath;
                }

                // Update the brand with the provided data
                $brand->update($brandData);
            } else {
                // Create a new brand
                if (isset($brandData['logo'])) {
                    // Store the logo
                    $logoPath = $brandData['logo']->store('brands', 'public');
                    $brandData['logo'] = $logoPath;
                }

                // Create the new brand
                $brand = Brand::create($brandData);
            }

            // Add the processed brand (updated or created) to the result array
            $processedBrands[] = $brand;
        }
        if (isset($validatedData['remove_brands']) && is_array($validatedData['remove_brands'])) {
            foreach ($validatedData['remove_brands'] as $itemId) {
                $brand = Brand::find($itemId);
                if ($brand) {
                    if ($brand->logo) {
                        Storage::disk('public')->delete($brand->logo);
                    }
                    $brand->delete();
                }
            }
        }
        return response()->json($processedBrands, 200);
    }
}
