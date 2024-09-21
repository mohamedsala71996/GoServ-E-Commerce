<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;



class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
    //  */
    // public function handle(Request $request, Closure $next, $guard = null): Response
    // {
    //     if ($guard) {
    //         auth()->shouldUse($guard);
    //     }

    //     $token = $request->header('auth-token');

    //     if ($token) {
    //         $request->headers->set('Authorization', 'Bearer ' . $token, true);
    //     }

    //     try {
    //         // Authenticate the user using the provided token
    //         JWTAuth::parseToken()->authenticate();
    //     } catch (TokenExpiredException $e) {
    //         return response()->json(['error' => 'Token has expired'], 401);
    //     } catch (JWTException $e) {
    //         return response()->json(['error' => 'Token is invalid'], 401);
    //     }

    //     return $next($request);
    // }
}
