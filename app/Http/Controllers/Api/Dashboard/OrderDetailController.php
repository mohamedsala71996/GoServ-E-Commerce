<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\ReturnSetting;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    /**
     * Display orders by a specific status.
     *
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    private function getOrdersByStatus($status)
    {
        $orders = Order::where('status', $status)
            ->with('items.productColor.product') // Load order items and associated product colors
            ->get();

        $orderCount = $orders->count();

        return response()->json([
            'status' => 'success',
            "{$status}_order_count" => $orderCount,
            'data' => OrderResource::collection($orders)
        ], 200);
    }

    // Methods for each status:

    public function pendingOrders()
    {
        return $this->getOrdersByStatus('pending');
    }

    public function paidOrders()
    {
        return $this->getOrdersByStatus('paid');
    }

    public function failedOrders()
    {
        return $this->getOrdersByStatus('failed');
    }

    public function acceptedOrders()
    {
        return $this->getOrdersByStatus('accepted');
    }

    public function cancelledOrders()
    {
        return $this->getOrdersByStatus('cancelled');
    }

    public function outForDeliveryOrders()
    {
        return $this->getOrdersByStatus('out for delivery');
    }

    public function deliveredOrders()
    {
        return $this->getOrdersByStatus('delivered');
    }

    public function notReceivedOrders()
    {
        return $this->getOrdersByStatus('not received');
    }

    public function returnedOrders()//قيد الاسترجاع
    {
        return $this->getOrdersByStatus('returned');
    }

    public function outForDeliveryReturnedOrders()
    {
        return $this->getOrdersByStatus('out for delivery return');
    }
    public function deliveredReturnedOrders()
    {
        return $this->getOrdersByStatus('delivered return');
    }

    public function totalCompletedOrdersAmount()
    {

        // Get the return period from the settings
        $returnSetting = ReturnSetting::first();
        $return_period = $returnSetting ? $returnSetting->return_period : 0; // Default to 30 days if not set
        // Calculate the total amount of completed orders that are outside the return period
        $totalAmount = Order::where('status', 'delivered')
            ->where('updated_at', '<=', now()->subDays($return_period))
            ->sum('total_amount');

        // Count the number of completed orders that are outside the return period
        $completedOrdersCount = Order::where('status', 'delivered')
            ->where('updated_at', '<=', now()->subDays($return_period))
            ->count();

        return response()->json([
            'status' => 'success',
            'total_amount' => $totalAmount,
            'order_count' => $completedOrdersCount
        ], 200);
    }
}
