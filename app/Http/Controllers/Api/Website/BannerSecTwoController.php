<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerSecTwoResource;
use App\Models\BannerSecTwo;
use Illuminate\Http\Request;

class BannerSecTwoController extends Controller
{

    public function index()
    {
        // Retrieve BannerSecTwo entry
        $banner = BannerSecTwo::first();
    // Check if a banner was found
    if (!$banner) {
        return response()->json(['message' => 'Banner not found'], 404);
    }
        // Return the entry as a JSON response
        return new BannerSecTwoResource($banner);
    }


}
