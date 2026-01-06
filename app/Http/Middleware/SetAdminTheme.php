<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\View;

class SetAdminTheme
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $allowed = ['dark','light'];

        $theme = session('admin_theme') ?: Cookie::get('admin_theme');
        if (!in_array($theme, $allowed)) {
            $theme = 'dark';
        }

        // share with all views
        View::share('adminTheme', $theme);

        return $next($request);
    }
}
