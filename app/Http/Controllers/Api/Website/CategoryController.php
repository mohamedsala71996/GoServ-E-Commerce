<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::withCount('products')->get();

        return CategoryResource::collection($categories);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return new CategoryResource($category);
    }
}
