<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\GlobalOfferRequest;
use App\Models\GlobalOffer;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class GlobalOfferController extends Controller
{
    public function index()
    {
        $offers = GlobalOffer::latest()->first();
        return response()->json($offers);
    }

}
