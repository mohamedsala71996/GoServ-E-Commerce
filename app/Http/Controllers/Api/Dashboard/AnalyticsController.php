<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OutOfStockResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductColor;
use App\Models\ProductColorSize;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function visitorCount()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Count visitors and sum visit_count for the current month
        $totalVisitors = Visitor::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $totalVisits = Visitor::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('visit_count');

        return response()->json([
            'status' => 'success',
            'total_visitors' => $totalVisitors,
            'total_visits' => $totalVisits,
        ]);
    }

    public function totalCompletedOrdersAmount()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Sum total_amount for completed orders in the current month
        $totalAmount = Order::where('status', 'delivered')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');

        return response()->json([
            'status' => 'success',
            'total_amount' => $totalAmount
        ], 200);
    }


    public function orderCount()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Count orders for the current month
        $totalOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return response()->json([
            'status' => 'success',
            'total_orders' => $totalOrders
        ], 200);
    }


    public function monthlySales()
    {
        // Get the current year
        $currentYear = Carbon::now()->year;

        // Retrieve monthly sales data for the current year
        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total_sales')
            ->whereYear('created_at', $currentYear)
            ->where('status', 'delivered')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->pluck('total_sales', 'month')
            ->toArray();

        // Ensure all months are included with default value of 0 if missing
        $salesData = array_replace(array_fill(1, 12, 0), $monthlySales);

        return response()->json([
            'status' => 'success',
            'year' => $currentYear,
            'monthly_sales' => $salesData
        ], 200);
    }

    public function dailySales()
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Retrieve daily sales data for the current month
        $dailySales = Order::selectRaw('DAY(created_at) as day, SUM(total_amount) as total_sales')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'delivered')
            ->groupBy(DB::raw('DAY(created_at)'))
            ->orderBy(DB::raw('DAY(created_at)'))
            ->pluck('total_sales', 'day')
            ->toArray();

        // Ensure all days of the month are included with default value of 0 if missing
        $daysInMonth = Carbon::now()->daysInMonth;
        $salesData = array_replace(array_fill(1, $daysInMonth, 0), $dailySales);

        return response()->json([
            'status' => 'success',
            'year' => $currentYear,
            'month' => $currentMonth,
            'daily_sales' => $salesData
        ], 200);
    }


    public function getNotifications()
    {
        // Retrieve the latest cart entries
        $carts = Cart::latest()->get();

        // Initialize an array to hold the notifications
        $notifications = [];

        // Loop through each cart entry to generate notifications
        foreach ($carts as $cart) {
            // Find the user who added the product to the cart
            $user = User::find($cart->user_id);

            // Get the translated product name
            $productName = $cart->productColorSize->productColor->product->getTranslation('name', app()->getLocale());

            // Generate the notification message
            $message = $user->name . ' ' . __('added') . ' ' . $productName . ' ' . __('to the cart');

            // Retrieve the photos for the product color
            $photos = json_decode($cart->productColorSize->productColor->photos, true);

            // Get the first photo if available
            $firstPhoto = $photos[0] ?? null;

            // Add the message and photo to the notifications array
            $notifications[] = [
                'message' => $message,
                'photo' => $firstPhoto, // URL or path to the photo
            ];
        }

        // Return the notifications in the API response
        return response()->json([
            'status' => 'success',
            'notifications' => $notifications
        ], 200);
    }

    public function outOfStock()
    {
        $outOfStockColorSizes = ProductColorSize::getOutOfStock();

        return response()->json([
            'status' => 'success',
            'data' => OutOfStockResource::collection($outOfStockColorSizes)
        ], 200);
    }

    public  function latestOrders()
    {
        $orders= Order::with('items')->where('status', 'paid')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => OrderResource::collection($orders)
        ], 200);
    }

}
