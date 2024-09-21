<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Dashboard\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function usersCount()
    {
        $usersCount = User::count();

        return response()->json(['users_count' => $usersCount], 200);
    }

}
