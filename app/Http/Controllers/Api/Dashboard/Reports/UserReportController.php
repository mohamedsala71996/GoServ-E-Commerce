<?php

namespace App\Http\Controllers\Api\Dashboard\Reports;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserReportController extends Controller
{
    public function getCustomerStatistics()
    {
        // Count distinct customers who have placed orders
        $purchasingCustomersCount = Order::distinct('user_id')->count('user_id');

        // Total number of customers
        $totalCustomersCount = User::count();

        // Calculate the number of non-purchasing customers
        $nonPurchasingCustomersCount = $totalCustomersCount - $purchasingCustomersCount;

        return response()->json([
            'status' => 'success',
            'purchasing_customers_count' => $purchasingCustomersCount,
            'non_purchasing_customers_count' => $nonPurchasingCustomersCount,
        ]);
    }

    public function customerSatisfactionRating(Request $request)
    {
        // Validate date range
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch ratings within the date range
        $ratings = DB::table('product_reviews')
            ->select('rating', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('rating')
            ->get();

        // Total reviews count
        $totalReviews = $ratings->sum('count');

        // Calculate percentage of satisfied customers (4 or 5 stars)
        $satisfiedReviews = $ratings->whereIn('rating', [4, 5])->sum('count');
        $satisfactionPercentage = $totalReviews > 0 ? ($satisfiedReviews / $totalReviews) * 100 : 0;

        // Initialize counts for each rating
        $ratingCounts = [
            '5_star' => 0,
            '4_star' => 0,
            '3_star' => 0,
            '2_star' => 0,
            '1_star' => 0,
        ];

        // Populate the rating counts
        foreach ($ratings as $rating) {
            if ($rating->rating == 5) {
                $ratingCounts['5_star'] = $rating->count;
            } elseif ($rating->rating == 4) {
                $ratingCounts['4_star'] = $rating->count;
            } elseif ($rating->rating == 3) {
                $ratingCounts['3_star'] = $rating->count;
            } elseif ($rating->rating == 2) {
                $ratingCounts['2_star'] = $rating->count;
            } elseif ($rating->rating == 1) {
                $ratingCounts['1_star'] = $rating->count;
            }
        }

        return response()->json([
            'status' => 'success',
            'satisfaction_percentage' => $satisfactionPercentage .'%',
            'total_reviews' => $totalReviews,
            'rating_breakdown' => $ratingCounts,
        ]);
    }


    public function topPayingCustomers(Request $request)
    {
        // Validate the date inputs if needed
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Query to get top paying customers
        $query = Order::select('user_id', DB::raw('SUM(total_amount) as total_paid'))
            ->groupBy('user_id')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('total_paid', 'desc');

        // Limit to top 10 paying customers
        $topPayingCustomers = $query->take(10)->get();

        // Load user data and include total paid
        $topPayingCustomers->map(function ($order) {
            // $order->user = User::find($order->user_id);
            return [
                'user_id' => $order->user_id,
                'user' => $order->user,
                'total_paid' => $order->total_paid,
            ];
        });

        return response()->json([
            'status' => 'success',
            'top_paying_customers' => $topPayingCustomers,
        ]);
    }
}
