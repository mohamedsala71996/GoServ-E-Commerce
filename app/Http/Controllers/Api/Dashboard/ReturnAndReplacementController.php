<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ReturnAndReplacement;
use Illuminate\Http\Request;

class ReturnAndReplacementController extends Controller
{

    public function getReturnRequests()
    {
        $returnRequests = ReturnAndReplacement::where('status','pending')->with('order', 'user')->get();

        return response()->json([
            'status' => 'success',
            'data' => $returnRequests
        ], 200);
    }



    public function updateReturnStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $returnRequest = ReturnAndReplacement::findOrFail($id);

        // Update the return request status
        $returnRequest->status = $request->status;
        $returnRequest->save();

        if ($request->status === 'approved') {
            // Find the associated order
            $order = Order::findOrFail($returnRequest->order_id);

            // Update the order status to 'returned' or any other appropriate status
            $order->status = 'returned'; // Or other status as needed
            $order->save();

            // Iterate through order items to update product color quantities
            foreach ($order->items as $item) {
                $productColor = $item->productColor;

                // Increase the quantity of the product color
                $productColor->quantity += $item->quantity;
                $productColor->save();
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Return request status updated successfully',
            'data' => $returnRequest
        ], 200);
    }

}
