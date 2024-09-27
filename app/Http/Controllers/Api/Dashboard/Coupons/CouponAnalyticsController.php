<?php

namespace App\Http\Controllers\Api\Dashboard\Coupons;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Coupon;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class CouponAnalyticsController extends Controller
{
    public function getCouponUsageStats($couponId)
    {
        // Fetch the coupon
        $coupon = Coupon::findOrFail($couponId);

        // Get usage count, unique user count, and total amount for different time frames
        $dailyUsage = $this->getCouponUsageForPeriod($coupon->id, 'day');
        $weeklyUsage = $this->getCouponUsageForPeriod($coupon->id, 'week');
        $monthlyUsage = $this->getCouponUsageForPeriod($coupon->id, 'month');
        $yearlyUsage = $this->getCouponUsageForPeriod($coupon->id, 'year');
        $sinceStartUsage = $this->getCouponUsageSinceStart($coupon->id, $coupon->created_at);

        $dailyUsersCount = $this->getCouponUsersCountForPeriod($coupon->id, 'day');
        $weeklyUsersCount = $this->getCouponUsersCountForPeriod($coupon->id, 'week');
        $monthlyUsersCount = $this->getCouponUsersCountForPeriod($coupon->id, 'month');
        $yearlyUsersCount = $this->getCouponUsersCountForPeriod($coupon->id, 'year');
        $sinceStartUsersCount = $this->getCouponUsersCountSinceStart($coupon->id, $coupon->created_at);

        $dailyTotalAmount = $this->getCouponTotalAmountForPeriod($coupon->id, 'day');
        $weeklyTotalAmount = $this->getCouponTotalAmountForPeriod($coupon->id, 'week');
        $monthlyTotalAmount = $this->getCouponTotalAmountForPeriod($coupon->id, 'month');
        $yearlyTotalAmount = $this->getCouponTotalAmountForPeriod($coupon->id, 'year');
        $sinceStartTotalAmount = $this->getCouponTotalAmountSinceStart($coupon->id, $coupon->created_at);

        return response()->json([
            'coupon' => $coupon->code,
            'daily_usage' => $dailyUsage,
            'weekly_usage' => $weeklyUsage,
            'monthly_usage' => $monthlyUsage,
            'yearly_usage' => $yearlyUsage,
            'since_start_usage' => $sinceStartUsage,
            'daily_users_count' => $dailyUsersCount,
            'weekly_users_count' => $weeklyUsersCount,
            'monthly_users_count' => $monthlyUsersCount,
            'yearly_users_count' => $yearlyUsersCount,
            'since_start_users_count' => $sinceStartUsersCount,
            'daily_total_amount' => $dailyTotalAmount,
            'weekly_total_amount' => $weeklyTotalAmount,
            'monthly_total_amount' => $monthlyTotalAmount,
            'yearly_total_amount' => $yearlyTotalAmount,
            'since_start_total_amount' => $sinceStartTotalAmount,
        ]);
    }

    /**
     * Helper function to get coupon usage for a specific period (daily, weekly, monthly, yearly).
     */
    private function getCouponUsageForPeriod($couponId, $period)
    {
        $query = Order::where('coupon_id', $couponId)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered']);

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                // Set the start of the week to Saturday and end of the week to Friday
                $startOfWeek = Carbon::now()->startOfWeek(Carbon::SATURDAY);
                $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }

        return $query->count();
    }

    /**
     * Helper function to get coupon usage since its start date.
     */
    private function getCouponUsageSinceStart($couponId, $startDate)
    {
        return Order::where('coupon_id', $couponId)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->where('created_at', '>=', $startDate)
            ->count();
    }

    /**
     * Helper function to get unique user count for a specific period.
     */
    private function getCouponUsersCountForPeriod($couponId, $period)
    {
        $query = Order::where('coupon_id', $couponId)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered']);

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }

        return $query->distinct('user_id')->count('user_id');
    }

    /**
     * Helper function to get unique user count since coupon's start date.
     */
    private function getCouponUsersCountSinceStart($couponId, $startDate)
    {
        return Order::where('coupon_id', $couponId)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->where('created_at', '>=', $startDate)
            ->distinct('user_id')
            ->count('user_id');
    }

    /**
     * Helper function to get total amount for a specific period.
     */
    private function getCouponTotalAmountForPeriod($couponId, $period)
    {
        $query = Order::where('coupon_id', $couponId)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered']);

        switch ($period) {
            case 'day':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
        }

        return $query->sum(DB::raw('total_amount - coupon_discount')); // Assuming 'total_cost' is the field that holds the order amount
    }

    /**
     * Helper function to get total amount since coupon's start date.
     */
    private function getCouponTotalAmountSinceStart($couponId, $startDate)
    {
        return Order::where('coupon_id', $couponId)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->where('created_at', '>=', $startDate)
            ->sum(DB::raw('total_amount - coupon_discount'));
    }


    public function getCouponOrders(Request $request, $couponId)
    {
        // Fetch the coupon
        $coupon = Coupon::findOrFail($couponId);

        // Fetch the orders related to the coupon
        $orders = Order::with('items')->where('coupon_id', $coupon->id)
            ->whereIn('status', ['accepted', 'out for delivery', 'delivered'])
            ->get();

        return response()->json([
            'coupon' => $coupon->code,
            'orders' => OrderResource::collection($orders),
        ]);
    }



    public function searchCoupons(Request $request)
    {
        $query = Coupon::query();

        // Search by coupon name (code)
        if ($request->filled('name')) {
            $query->where('code', 'like', '%' . $request->input('name') . '%');
        }

        // Filter by status array
        if ($request->filled('status')) {
            $statuses = $request->input('status'); // This should be an array

            $query->where(function ($q) use ($statuses) {
                // Check each status independently
                if (in_array('active', $statuses)) {
                    $q->orWhere('status', 'active');
                }
                if (in_array('inactive', $statuses)) {
                    $q->orWhere('status', 'inactive');
                }
                if (in_array('ended', $statuses)) {
                    $q->orWhere('valid_until', '<', now());
                }
                if (in_array('not_started', $statuses)) {
                    $q->orWhere('valid_from', '>', now());
                }
            });
        }

        // Handle sorting arrangements
        if ($request->filled('arrangement')) {
            $arrangement = $request->input('arrangement');

            switch ($arrangement) {
                case 'sort_by_characters':
                    $query->orderBy('code');
                    break;
                case 'sort_by_start_date':
                    $query->orderBy('valid_from');
                    break;
                case 'sort_by_end_date':
                    $query->orderBy('valid_until');
                    break;
                case 'most_sales':
                    $query->orderBy('used_count', 'desc');
                    break;
            }
        }

        // Handle sorting by creation date (older or latest)
        if ($request->filled('sort_by')) {
            $sortBy = $request->input('sort_by');

            if ($sortBy == 'older') {
                $query->orderBy('created_at', 'asc'); // Oldest first
            } elseif ($sortBy == 'latest') {
                $query->orderBy('created_at', 'desc'); // Newest first
            }
        }

        // Return the result
        return $query->get();
    }
}
