<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUsersWithOrdersCount()
{
    // Get the count of users who have placed at least one order
    $usersWithOrdersCount = User::has('orders')->count();

    return response()->json([
        'users_with_orders_count' => $usersWithOrdersCount,
    ]);
}


public function getUsersWithFirstOrderCount()
{
    // Get the count of users who have made exactly one order
    $usersWithFirstOrderCount = User::whereHas('orders', function ($query) {
        $query->groupBy('user_id')
              ->havingRaw('COUNT(*) = 1');
    })->count();
    $users = User::whereHas('orders', function ($query) {
        $query->groupBy('user_id')
              ->havingRaw('COUNT(*) = 1');
    })->get();

    return response()->json([
        'users_with_first_order_count' => $usersWithFirstOrderCount,
        'users' => $users,
    ]);
}
}
