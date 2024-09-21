<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShippingSetting;
use Illuminate\Http\Request;

class ShippingSettingsController extends Controller
{
    // Retrieve the shipping settings
    public function index()
    {
        $shippingSettings = ShippingSetting::first();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $shippingSettings
        ], 200);
    }

    // Update the shipping settings
    public function update(Request $request)
    {
        $request->validate([
            'default_rate' => 'required|numeric|min:0',
        ]);

        $shippingSettings = ShippingSetting::first();

        if ($shippingSettings) {
            $shippingSettings->update($request->all());
        } else {
            $shippingSettings = ShippingSetting::create($request->all());
        }

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'data' => $shippingSettings
        ], 200);
    }

    public function destroy()
    {
        $shippingSettings = ShippingSetting::first();

        if (!$shippingSettings) {
            return response()->json([
                'status' => 'error',
                'code' => 404,
                'message' => 'Shipping settings not found'
            ], 404);
        }

        $shippingSettings->delete();

        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'Shipping settings deleted successfully'
        ], 200);
    }
}
