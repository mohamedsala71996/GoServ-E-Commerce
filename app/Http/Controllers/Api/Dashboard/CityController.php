<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    // Retrieve all cities
    public function index()
    {
        $cities = City::all();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => CityResource::collection($cities)
        ], 200);
    }

    // Store a newly created city
    public function store(Request $request)
    {
        $request->validate([
            'name.en' => [
                'required',
                'string',
                'unique:cities,name->en'
            ],
            'name.ar' => [
                'required',
                'string',
                'unique:cities,name->ar'
            ],
            'shipping_rate' => 'required|numeric|min:0',
        ]);

        $city = City::create($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $city
        ], 201);
    }


    // Store several cities
    public function storeSeveralCities(Request $request)
    {
        $data = $request->validate([
            'cities' => 'required|array',
            'cities.*.name.en' => 'required|string|unique:cities,name->en',
            'cities.*.name.ar' => 'required|string|unique:cities,name->ar',
            'cities.*.shipping_rate' => 'required|numeric|min:0',
        ]);

        $cities = [];

        foreach ($data['cities'] as $cityData) {
            $cities[] = City::create($cityData);
        }

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $cities
        ], 201);
    }

    // Update several cities
    public function updateSeveralCities(Request $request)
    {
        $data = $request->validate([
            'cities' => 'required|array',
            'cities.*.id' => 'sometimes|exists:cities,id',
            'cities.*.name.en' => 'required|string',
            'cities.*.name.ar' => 'required|string',
            'cities.*.shipping_rate' => 'required|numeric|min:0',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:cities,id'
        ]);

        $cities = [];
        $cityIdsToRemove = $request->input('remove_items', []);

        foreach ($data['cities'] as $cityData) {
            if (isset($cityData['id'])) {
                // Update existing city
                $city = City::find($cityData['id']);
                if ($city) {
                    // Check if the name already exists in another city
                    $nameExists = City::where(function ($query) use ($cityData) {
                        $query->where('name->ar', $cityData['name']['ar'])
                            ->orWhere('name->en', $cityData['name']['en']);
                    })->where('id', '!=', $city->id)->exists();

                    if ($nameExists) {
                        return response()->json([
                            'status' => 'error',
                            'code' => 422,
                            'message' => "The name '{$cityData['name']['en']}' (English) or '{$cityData['name']['ar']}' (Arabic) has already been taken."
                        ], 422);
                    }

                    $city->update($cityData);
                    $cities[] = $city;
                }
            } else {
                // Create new city
                $nameExists = City::where(function ($query) use ($cityData) {
                    $query->where('name->ar', $cityData['name']['ar'])
                        ->orWhere('name->en', $cityData['name']['en']);
                })->exists();

                if ($nameExists) {
                    return response()->json([
                        'status' => 'error',
                        'code' => 422,
                        'message' => "The name '{$cityData['name']['en']}' (English) or '{$cityData['name']['ar']}' (Arabic) has already been taken."
                    ], 422);
                }

                $cities[] = City::create($cityData);
            }
        }

        // Delete cities
        if (!empty($cityIdsToRemove)) {
            City::whereIn('id', $cityIdsToRemove)->delete();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $cities
        ], 200);
    }


    // Update a specific city
    public function update(Request $request, $id)
    {
        $request->validate([
            'name.en' => 'required|string|unique:cities,name->en,' . $id,
            'name.ar' => 'required|string|unique:cities,name->ar,' . $id,
            'shipping_rate' => 'required|numeric|min:0',
        ]);

        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'City not found'
            ], 404);
        }

        $city->update($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $city
        ], 200);
    }
    // Remove a specific city
    public function destroy($id)
    {
        $city = City::find($id);

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'City not found'
            ], 404);
        }

        $city->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'City deleted successfully'
        ], 200);
    }
}
