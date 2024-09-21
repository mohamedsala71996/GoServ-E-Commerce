<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\ReturnAndReplacement;
use App\Models\ReturnSetting; // Import the ReturnSetting model
use App\Models\Order; // Import the Order model if needed
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnAndReplacementController extends Controller
{
    public function requestReturn(Request $request)
    {
        $request->validate([
            'order_id' => ['required','exists:orders,id',Rule::unique('returns_and_replacements')
            ],
            'reason' => 'required|string'
        ]);

        // Fetch the return period from the settings
        $returnSetting = ReturnSetting::first(); // Adjust this if you have a specific way to retrieve the setting
        $returnPeriod = $returnSetting ? $returnSetting->return_period : 30; // Default to 30 days if not set

        // Fetch the order to check its creation date
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        // Check if the order is within the return period
        if ($order->created_at->diffInDays(now()) > $returnPeriod) {
            return response()->json([
                'status' => 'error',
                'message' => 'Return period has expired'
            ], 400);
        }

        // Create the return request if within the allowed period
        $returnRequest = ReturnAndReplacement::create([
            'order_id' => $request->order_id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Return request submitted successfully',
            'data' => $returnRequest
        ], 201);
    }
}
