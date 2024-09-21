<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getUserOrders()
    {
        // Get all orders for the authenticated user with related items
        $orders = Order::where('user_id', auth()->id())
            ->with(['items.productColorSize']) // Include related order items and product colors
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ]);
    }
}

