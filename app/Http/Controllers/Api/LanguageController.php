<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;

class LanguageController extends Controller
{
    public function setLanguage($lang)
    {
        // Check if the requested language is supported
        if (!in_array($lang, ['en', 'ar'])) {
            return response()->json(['status' => 'error', 'message' => 'Unsupported language'], 400);
        }

        // Set the language for the current request
        App::setLocale($lang);

        // Store the selected language in a cookie (set for 1 year)
        $cookie = Cookie::make('app_language', $lang, 60 * 24 * 365);

        // Respond with a success message and set the cookie
        return response()->json(['status' => 'success', 'message' => 'Language changed to ' . $lang])
                         ->withCookie($cookie);
    }
}
