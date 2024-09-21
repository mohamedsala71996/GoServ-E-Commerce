<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\ContactRequest;
use App\Http\Requests\Api\Dashboard\StoreContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Admin;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // public function __construct()
    // {
    //     // Apply middleware to the constructor
    //     $this->middleware(function ($request, $next) {
    //         if ( !auth()->user() instanceof Admin) {
    //             return response()->json(['message' => 'You are not authenticated as an admin.'], 401);
    //         }
    //         return $next($request);
    //     });
    // }
    public function index()
    {
        $contacts = Contact::all();
        return ContactResource::collection($contacts);
    }

    public function store(ContactRequest $request)
    {
        $data = $request->validated();
        $contact = Contact::create($data);
        return response()->json($contact, 201);
    }
    public function update(ContactRequest $request, $id)
    {
        $data = $request->validated();
        $contact = Contact::findOrFail($id);
        $contact->update($data);
        return response()->json($contact);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(['message' => 'Contact deleted successfully']);
    }

}
