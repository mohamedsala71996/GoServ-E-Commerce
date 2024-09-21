<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class changeLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // app()->setlocale('ar');

        // if (isset($request->lang) && $request->lang == 'en') {
        //     app()->setlocale('en');
        // }
        // return $next($request);

              $language = $request->cookie('app_language', 'en'); // Default to English if cookie not found
              App::setLocale($language);

              return $next($request);
    }
}
