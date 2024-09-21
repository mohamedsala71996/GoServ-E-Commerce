<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserLoginRequest; // You can create a similar request for Admin
use App\Http\Requests\Api\Auth\UserRegisterRequest; // You can create a similar request for Admin
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $validated = $request->validated();

        $admin = Admin::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $admin->createToken('Admin',['role:admin'])->plainTextToken;

        return response()->json([
            'message' => 'Admin registered successfully.',
            // 'admin' => $admin,
            // 'token' => $token,
        ]);
    }

    // Log in an admin
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            $token = $admin->createToken('Admin',['role:admin'])->plainTextToken;

            return response()->json([
                'message' => 'Admin logged in successfully.',
                'admin' => $admin,
                'token' => $token,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials.'], 401);
    }

    // Log out an admin
    public function logout(Request $request)
    {
        $admin = Auth::guard('sanctum')->user();

        if (!$admin) {
            // Log or debug information
            // \Log::info('No authenticated admin found during logout attempt.');
            return response()->json(['message' => 'No authenticated user found.'], 401);
        }

        $admin->currentAccessToken()->delete();
        return response()->json(['message' => 'Admin logged out successfully.']);
        }

}
