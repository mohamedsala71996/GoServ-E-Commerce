<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\OrderTax;
use Illuminate\Http\Request;

class OrderTaxController extends Controller
{

    public function index()
    {
        $tax = OrderTax::first();
        return response()->json([
            'success' => true,
            'data' => $tax
        ], 200);
    }

    // Create a new tax
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        $existingTax = OrderTax::first();

        if ($existingTax) {
            return response()->json([
                'success' => false,
                'message' => 'Tax already exists. You cannot create more than one tax.',
            ], 400);
        }
        $tax = OrderTax::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tax created successfully',
            'data' => $tax
        ], 201);
    }


    // Update a tax
    public function update(Request $request)
    {
        $tax = OrderTax::first();

        if (!$tax) {
            return response()->json([
                'success' => false,
                'message' => 'Tax not found'
            ], 404);
        }

        $validated = $request->validate([
            'rate' => 'sometimes|numeric|min:0|max:100',
        ]);

        $tax->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tax updated successfully',
            'data' => $tax
        ], 200);
    }

    // Delete a tax
    public function destroy(Request $request)
    {
        $tax = OrderTax::first();

        if (!$tax) {
            return response()->json([
                'success' => false,
                'message' => 'Tax not found'
            ], 404);
        }

        $tax->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tax deleted successfully'
        ], 200);
    }
}
