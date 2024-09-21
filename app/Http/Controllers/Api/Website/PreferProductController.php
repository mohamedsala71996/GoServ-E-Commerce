<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\PreferProductResource;
use App\Models\PreferProduct;
use Illuminate\Http\Request;

class PreferProductController extends Controller
{

    public function index()
    {
        $preferProducts = PreferProduct::with('product')->take(3)->get();

        return PreferProductResource::collection($preferProducts);
    }


}
