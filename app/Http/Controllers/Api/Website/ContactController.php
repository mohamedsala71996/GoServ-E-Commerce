<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\Request;

class ContactController extends Controller
{

    public function index()
    {
        $contacts = Contact::orderBy('contact_type')->get();

        return ContactResource::collection($contacts);
    }


}
