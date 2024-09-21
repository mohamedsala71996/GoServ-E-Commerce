<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\ColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Admin;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
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
        $colors = Color::get();

        return ColorResource::collection($colors);
    }

    // public function show($id)
    // {
    //     $color = Color::findOrFail($id);

    //     return response()->json($color);
    // }
    public function store(ColorRequest $request)
    {
        $data = $request->validated();
        $color = Color::create($data);

        return response()->json($color, 201);
    }
    public function update(ColorRequest $request, $id)
    {
        $data = $request->validated();
        $color = Color::findOrFail($id);
        $color->update($data);

        return response()->json($color);
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return response()->json(['message' => 'Color deleted successfully']);
    }
}
