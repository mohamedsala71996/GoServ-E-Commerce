<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\Product;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{

    public function index()
    {
        $links = SocialMedia::get();

        return response()->json([
            'status' => 200,
            'data' => $links
        ]);
    }


}
