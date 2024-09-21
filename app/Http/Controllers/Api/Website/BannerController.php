<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BannerSecOneResource;
use App\Models\Banner;
use App\Models\BannerSecOne;
use Illuminate\Http\Request;

class BannerController extends Controller
{

    public function index()
    {
        // Retrieve all banners with their items, ordered by the 'order' field
        $banners = Banner::with('items')->orderBy('order')->get();

        // Return the response with status and data
        return response()->json([

            'data' => BannerResource::collection($banners),
        ], 200);
    }
    public function show($id)
    {
        // Find the banner by its ID, along with its related items
        $banner = Banner::with('items')->findOrFail($id);

        // Return the response with the banner data
        return response()->json([
            'data' => new BannerResource($banner),
        ], 200);
    }

}
