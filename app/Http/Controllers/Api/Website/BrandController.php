<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{

    public function index()
    {
        $brands = Brand::with('products')->get();

        return BrandResource::collection($brands);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return new BrandResource($brand);
    }
}
