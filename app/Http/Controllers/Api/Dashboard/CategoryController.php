<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreCategoryRequest;
use App\Http\Requests\Api\Dashboard\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Admin;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
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
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }

    // public function show(Category $category)
    // {
    //     return response()->json($category);
    // }

    public function store(StoreCategoryRequest $request)
    {

        $data = $request->validated();
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('categories', 'public'); // Store photo in 'public/categories'
            $data['photo'] = $photoPath;
        }
        $category = Category::create($data);
        return response()->json($category, 201);
    }

    public function updateCategory(UpdateCategoryRequest $request)
    {
        $category=Category::find($request->category_id);
        $validatedData = $request->validated();

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($category->photo ) {
                Storage::disk('public')->delete($category->photo);
            }

            $photo = $request->file('photo');
            $photoPath = $photo->store('categories', 'public');
            $validatedData['photo'] = $photoPath;
        }

        $category->update($validatedData);
        return new CategoryResource($category);
    }

    public function destroy($id)
    {
        $category= Category::findOrFail($id);
        // Delete the photo if it exists
        if ($category->photo ) {
            Storage::disk('public')->delete($category->photo);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}
