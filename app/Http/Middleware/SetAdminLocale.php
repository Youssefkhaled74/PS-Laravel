<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetAdminLocale
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->get('lang');
        if ($lang && in_array($lang, ['ar','en'])) {
            session(['admin_locale' => $lang]);
        }

        $locale = session('admin_locale', 'ar');
        if (! in_array($locale, ['ar','en'])) {
            $locale = 'ar';
            session(['admin_locale' => $locale]);
        }

        App::setLocale($locale);

        return $next($request);
    }
}
