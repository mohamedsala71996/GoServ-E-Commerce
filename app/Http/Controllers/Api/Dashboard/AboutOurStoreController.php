<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreAboutOurStoreRequest;
use App\Http\Resources\AboutOurStoreResource;
use App\Models\AboutOurStore;
use App\Models\Admin;
use Illuminate\Http\Request;

class AboutOurStoreController extends Controller
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
        $desc = AboutOurStore::first();

        if (!$desc) {
            return response()->json(['message' => 'No data found'], 404);
        }

        return new AboutOurStoreResource($desc);
    }

    // public function show($id)
    // {
    //     $item = AboutOurStore::findOrFail($id);
    //     return new AboutOurStoreResource($item);
    // }

    public function store(StoreAboutOurStoreRequest $request)
    {
        $data = $request->validated();

        // Check if a record already exists
        $item = AboutOurStore::first();

        if ($item) {
            // Update existing record
            $item->update($data);
        } else {
            // Create a new record
            $item = AboutOurStore::create($data);
        }

        return response()->json($item, 201);
    }

    public function update(StoreAboutOurStoreRequest $request, $id)
    {
        $item = AboutOurStore::findOrFail($id);
        $data = $request->validated();
        $item->update($data);

        return new AboutOurStoreResource($item);
    }
    public function destroy($id)
    {
        $item = AboutOurStore::find($id); // Use find() instead of findOrFail()

        if ($item) {
            $item->delete();

            return response()->json([
                'message' => 'Deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'Nothing to delete'
        ], 404); // Use 404 Not Found for when the item doesn't exist
    }
}
