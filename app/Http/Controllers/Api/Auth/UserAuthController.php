<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\UserLoginRequest;
use App\Http\Requests\Api\Auth\UserRegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserAuthController extends Controller
{
        // Register a new user
        public function register(UserRegisterRequest $request)
        {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => bcrypt($validated['password']),
            ]);

            // Generate a new API token for the user
            $token = $user->createToken('GoServ',['role:user'])->plainTextToken;

            return response()->json([
                'message' => 'User registered successfully.',
                // 'user' => $user,
                // 'token' => $token,
            ]);
        }

        public function login(UserLoginRequest $request)
        {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('GoServ',['role:user'])->plainTextToken;

                return response()->json([
                    'message' => 'User logged in successfully.',
                    'user' => $user,
                    'token' => $token,
                ]);
            }

            return response()->json(['message' => 'Invalid credentials.'], 401);
        }


        // Log out the user
        public function logout(Request $request)
        {
            $user = Auth::guard('sanctum')->user();

            if (!$user) {
                // Log or debug information
                // \Log::info('No authenticated admin found during logout attempt.');
                return response()->json(['message' => 'No authenticated user found.'], 401);
            }

            $user->currentAccessToken()->delete();
            return response()->json(['message' => 'User logged out successfully.']);
            }

}
