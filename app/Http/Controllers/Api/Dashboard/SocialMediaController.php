<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\SocialMediaRequest;
use App\Models\Admin;
use App\Models\SocialMedia;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
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
        $socialMedia = SocialMedia::all(); // Retrieve all social media records

        return response()->json($socialMedia, 200); // Return with 200 OK status
    }
    public function store(SocialMediaRequest $request)
    {
        $validated = $request->validated();

        $socialMedia = SocialMedia::create($validated);

        return response()->json([
            'message' => 'Social media record created successfully.',
            'data' => $socialMedia
        ]);
    }

    public function update(SocialMediaRequest $request, $id)
    {
        $socialMedia = SocialMedia::findOrFail($id); // Find social media record by ID
        $validated = $request->validated(); // Validate the request data

        $socialMedia->update($validated); // Update the record

        return response()->json([
            'message' => 'Social media record updated successfully.',
            'data' => $socialMedia
        ]);
    }
    public function destroy($id)
    {
        $socialMedia = SocialMedia::findOrFail($id); // Find social media record by ID
        $socialMedia->delete(); // Delete the record

        return response()->json([
            'message' => 'Social media record deleted successfully.'
        ]);
    }

}
