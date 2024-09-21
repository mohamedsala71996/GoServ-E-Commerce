<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Api\Website\PaymobController;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\ReturnSetting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // public function updateStatus(Request $request, $orderId)
    // {
    //     $request->validate([
    //         'status' => 'required|in:pending,paid,failed,accepted,cancelled,out for delivery,delivered,not received,returned,out for delivery return,delivered return'
    //     ]);

    //     $order = Order::find($orderId);

    //     if (!$order) {
    //         return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
    //     }

    //     $order->status = $request->status;
    //     $order->save();

    //     return response()->json(['status' => 'success', 'message' => 'Order status updated successfully', 'order' => $order], 200);
    // }
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,failed,accepted,cancelled,out for delivery,delivered,not received,returned,out for delivery return,delivered return'
        ]);

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        if ($request->status == 'cancelled' && $order->status == 'paid') {
            // Initiate refund
            $paymobController = new PaymobController();
            $refundResponse = $paymobController->refund(new Request(['order_id' => $orderId]));

            if ($refundResponse->status() != 200) {
                return $refundResponse;
            }
        }

        $order->status = $request->status;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Order status updated successfully', 'order' => $order], 200);
    }
    public function deleteOrder($orderId)
{
    $order = Order::find($orderId);

    if (!$order) {
        return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
    }

    $order->delete();

    return response()->json(['status' => 'success', 'message' => 'Order deleted successfully'], 200);
}
}
