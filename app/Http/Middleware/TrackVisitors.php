<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class TrackVisitors
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $visitor = Visitor::where('ip_address', $ip)->first();

        if ($visitor) {
            $visitor->increment('visit_count');
        } else {
            Visitor::create(['ip_address' => $ip]);
        }

        return $next($request);
    }
}
