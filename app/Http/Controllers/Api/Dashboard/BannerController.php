<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Models\BannerItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        // Retrieve all banners with their items, ordered by the 'order' field
        $banners = Banner::with('items')->orderBy('order')->get();

        // Return the response with status and data
        return response()->json([

            'data' => BannerResource::collection($banners),
        ], 200);
    }
    public function show($id)
    {
        // Find the banner by its ID, along with its related items
        $banner = Banner::with('items')->findOrFail($id);

        // Return the response with the banner data
        return response()->json([
            'data' => new BannerResource($banner),
        ], 200);
    }
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string',
            'order' => 'integer',
            'items' => 'array',
            'items.*.title.en' => 'required|string|max:255',
            'items.*.title.ar' => 'required|string|max:255',
            'items.*.description.en' => 'nullable|string|max:1000',
            'items.*.description.ar' => 'nullable|string|max:1000',
            'items.*.link' => 'required|string',
            'items.*.photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Create the banner
        $banner = Banner::create($request->only(['name', 'order']));

        // Create banner items
        if ($request->has('items')) {
            foreach ($request->items as $index => $itemData) {
                // Handle the photo file
                if ($request->hasFile("items.{$index}.photo")) {
                    $file = $request->file("items.{$index}.photo");
                    $filePath = $file->store('banner_photos', 'public');
                    $itemData['photo'] = $filePath;
                }

                // Create the banner item
                $banner->items()->create($itemData);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Banner and items created successfully',
            'data' => new BannerResource($banner->load('items')),
        ], 201);
    }


    public function updateBanner(Request $request)
    {
        $banner = Banner::find($request->banner_id);
        // Validate the incoming request
     $data =$request->validate([
            'name' => 'required|string',
            'order' => 'integer',
            'items' => 'array',
            'items.*.id' => 'sometimes|exists:banner_items,id',
            'items.*.title' => 'required|array',
            'items.*.description' => 'required|array',
            'items.*.link' => 'nullable|string',
            'items.*.photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'items.*.order' => 'integer',
            'remove_items' => 'nullable|array',
            'remove_items.*' => 'integer|exists:banner_items,id'
        ]);

        // Update the banner
        $banner->update($request->only(['name', 'order']));

        // Update or create banner items
        if ($request->has('items')) {
            foreach ($request->input('items') as $index => $itemData) {
                if (isset($itemData['id'])) {
                    // Update existing item
                    $item = BannerItem::find($itemData['id']);

                    if ($request->hasFile("items.{$index}.photo")) {
                        // Delete old photo if it exists
                        if ($item->photo) {
                            Storage::disk('public')->delete($item->photo);
                        }

                        // Store new photo
                        $file = $request->file("items.{$index}.photo");
                        $filePath = $file->store('banner_photos', 'public');
                        $itemData['photo'] = $filePath;
                    }

                    $item->update($itemData);
                } else {
                    // Create new item
                    if ($request->hasFile("items.{$index}.photo")) {
                        $file = $request->file("items.{$index}.photo");
                        $filePath = $file->store('banner_photos', 'public');
                        $itemData['photo'] = $filePath;
                    }

                    $banner->items()->create($itemData);
                }
            }
        }
        if (isset($data['remove_items']) && is_array($data['remove_items'])) {
            foreach ($data['remove_items'] as $itemId) {
                $item = $banner->items()->find($itemId);
                if ($item) {
                    if ($item->photo) {
                        Storage::disk('public')->delete($item->photo);
                    }
                    $item->delete();
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Banner and items updated successfully',
            'data' => new BannerResource($banner->load('items')),
        ], 200);
    }

    public function destroy(Banner $banner)
    {
        // Delete associated items' photos
        $banner->items->each(function ($item) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
        });

        // Delete the banner
        $banner->delete();

        // Return success response with message
        return response()->json([
            'status' => 'success',
            'message' => 'Banner and associated items deleted successfully',
        ], 200);
    }
}
