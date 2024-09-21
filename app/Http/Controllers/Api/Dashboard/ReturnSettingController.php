<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ReturnSetting;
use Illuminate\Http\Request;

class ReturnSettingController extends Controller
{
    // Retrieve the current return period setting
    public function index()
    {
        $setting = ReturnSetting::first();
        return response()->json([
            'return_period' => $setting ? $setting->return_period : 30,
        ]);
    }

    // Update the return period setting
    public function update(Request $request)
    {
        $request->validate([
            'return_period' => 'required|integer|min:1',
        ]);

        $setting = ReturnSetting::firstOrCreate([]);
        $setting->return_period = $request->return_period;
        $setting->save();

        return response()->json([
            'message' => 'Return period updated successfully!',
            'return_period' => $setting->return_period,
        ]);
    }
}
