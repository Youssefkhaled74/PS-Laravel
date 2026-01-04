<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromHeader
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->header('Lang') ?: $request->header('Accept-Language');
        if ($lang) {
            $lang = strtolower(substr($lang, 0, 2));
        }

        $supported = ['en', 'ar'];
        if (! in_array($lang, $supported, true)) {
            $lang = 'en';
        }

        App::setLocale($lang);

        return $next($request);
    }
}
