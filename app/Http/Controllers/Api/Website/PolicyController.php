<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExchangeAndReturnPolicyResource;
use App\Http\Resources\PrivacyPolicyResource;
use App\Models\ExchangeAndReturnPolicy;
use App\Models\PrivacyPolicy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{

    public function getExchangeAndReturnPolicy()
    {
        $item = ExchangeAndReturnPolicy::where('status','active')->first();

        if (! $item) {
            return response()->json(['message' => 'No active Exchange and Return Policy found'], 404);
        }

        return new ExchangeAndReturnPolicyResource($item);
    }
    public function getPrivacyPolicy()
    {
        $item = PrivacyPolicy::where('status','active')->first();
        if (! $item) {
            return response()->json(['message' => 'No active Privacy Policy found'], 404);
        }

        return new PrivacyPolicyResource($item);
    }


}
