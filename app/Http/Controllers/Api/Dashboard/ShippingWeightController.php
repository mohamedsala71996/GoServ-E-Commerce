<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShippingWeight;
use Illuminate\Http\Request;

class ShippingWeightController extends Controller
{
    // Retrieve all shipping weights
    public function index()
    {
        $shippingWeights = ShippingWeight::all();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $shippingWeights
        ], 200);
    }

    // Store a newly created shipping weight
    public function store(Request $request)
    {
        $request->validate([
            'min_weight' => 'required|numeric|min:0',
            'max_weight' => 'required|numeric|min:0|gte:min_weight',
            'additional_rate' => 'required|numeric|min:0',
        ]);

        $shippingWeight = ShippingWeight::create($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $shippingWeight
        ], 201);
    }

    // Store several shipping weights
    public function storeSeveral(Request $request)
    {
        $data = $request->validate([
            'shipping_weights' => 'required|array',
            'shipping_weights.*.min_weight' => 'required|numeric|min:0',
            'shipping_weights.*.max_weight' => 'required|numeric|min:0|gte:shipping_weights.*.min_weight',
            'shipping_weights.*.additional_rate' => 'required|numeric|min:0',
        ]);

        $shippingWeights = [];

        foreach ($data['shipping_weights'] as $weightData) {
            $shippingWeights[] = ShippingWeight::create($weightData);
        }

        return response()->json([
            'status' => 'success',
            'code' => 201,
            'data' => $shippingWeights
        ], 201);
    }

    // Update several shipping weights
    public function updateSeveral(Request $request)
    {
        $data = $request->validate([
            'shipping_weights' => 'required|array',
            'shipping_weights.*.id' => 'sometimes|exists:shipping_weights,id',
            'shipping_weights.*.min_weight' => 'required|numeric|min:0',
            'shipping_weights.*.max_weight' => 'required|numeric|min:0|gte:shipping_weights.*.min_weight',
            'shipping_weights.*.additional_rate' => 'required|numeric|min:0',
            'remove_items' => 'sometimes|array',
            'remove_items.*' => 'integer|exists:shipping_weights,id'
        ]);

        $shippingWeights = [];
        $weightIdsToRemove = $request->input('remove_items', []);

        foreach ($data['shipping_weights'] as $weightData) {
            if (isset($weightData['id'])) {
                // Update existing shipping weight
                $weight = ShippingWeight::find($weightData['id']);
                if ($weight) {
                    $weight->update($weightData);
                    $shippingWeights[] = $weight;
                }
            } else {
                // Create new shipping weight
                $shippingWeights[] = ShippingWeight::create($weightData);
            }
        }

        // Delete shipping weights
        if (!empty($weightIdsToRemove)) {
            ShippingWeight::whereIn('id', $weightIdsToRemove)->delete();
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $shippingWeights
        ], 200);
    }

    // Update a specific shipping weight
    public function update(Request $request, $id)
    {
        $request->validate([
            'min_weight' => 'required|numeric|min:0',
            'max_weight' => 'required|numeric|min:0|gte:min_weight',
            'additional_rate' => 'required|numeric|min:0',
        ]);

        $shippingWeight = ShippingWeight::find($id);

        if (!$shippingWeight) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Shipping weight not found'
            ], 404);
        }

        $shippingWeight->update($request->all());

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $shippingWeight
        ], 200);
    }

    // Remove a specific shipping weight
    public function destroy($id)
    {
        $shippingWeight = ShippingWeight::find($id);

        if (!$shippingWeight) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Shipping weight not found'
            ], 404);
        }

        $shippingWeight->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Shipping weight deleted successfully'
        ], 200);
    }
}
