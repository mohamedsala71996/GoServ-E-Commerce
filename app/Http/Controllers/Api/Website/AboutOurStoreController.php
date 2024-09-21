<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutOurStoreResource;
use App\Models\AboutOurStore;

class AboutOurStoreController extends Controller
{

    public function index()
    {
        $desc = AboutOurStore::first();

        return new AboutOurStoreResource($desc);
    }


}
