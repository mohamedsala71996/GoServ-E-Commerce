<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExchangeAndReturnPolicyResource;
use App\Models\ExchangeAndReturnPolicy;
use Illuminate\Http\Request;

class ExchangeAndReturnPolicyController extends Controller
{

    public function index()
    {
        $items = ExchangeAndReturnPolicy::get();

        return  ExchangeAndReturnPolicyResource::collection($items);
    }


}
