<?php

namespace App\Http\Controllers\Api\Dashboard\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostRequestedReportController extends Controller
{

    public function mostRequestedDays(Request $request)
{
    // Validate the date inputs if needed
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query to get the count of orders per day
    $query = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as orders_count'))
        ->groupBy(DB::raw('DATE(created_at)'))
        ->orderBy('orders_count', 'desc');

    // Apply date filter if provided
    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Limit to top 10 days with most orders
    $mostRequestedDays = $query->take(10)->get();

    return response()->json([
        'status' => 'success',
        'most_requested_days' => $mostRequestedDays,
    ]);
}

public function mostRequestedHours(Request $request)
{
    // Validate the date inputs if needed
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query to get the count of orders per hour, formatted with AM/PM
    $query = Order::select(DB::raw('DATE_FORMAT(created_at, "%h %p") as hour'), DB::raw('COUNT(*) as orders_count'))
        ->groupBy(DB::raw('DATE_FORMAT(created_at, "%h %p")'))
        ->orderBy('orders_count', 'desc');

    // Apply date filter if provided
    if ($startDate && $endDate) {
        $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // Limit to top 10 hours with most orders
    $mostRequestedHours = $query->take(10)->get();

    return response()->json([
        'status' => 'success',
        'most_requested_hours' => $mostRequestedHours,
    ]);
}

public function topRequestsCustomers(Request $request)
{
    // Validate the date inputs if needed
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date',
    ]);

    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    // Query to get the count of orders per customer
    $query = User::join('orders', 'users.id', '=', 'orders.user_id')
        ->select('users.id', 'users.name', DB::raw('COUNT(orders.id) as orders_count'))
        ->groupBy('users.id', 'users.name')
        ->orderBy('orders_count', 'desc');

    // Apply date filter if provided
    if ($startDate && $endDate) {
        $query->whereBetween('orders.created_at', [$startDate, $endDate]);
    }

    // Limit to top 10 customers with most orders
    $topCustomers = $query->take(10)->get();

    return response()->json([
        'status' => 'success',
        'top_customers' => $topCustomers,
    ]);
}
}
