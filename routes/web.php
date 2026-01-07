<?php

use Illuminate\Support\Facades\Route;

// Web loader: include modular web route files (admin area, etc.)
Route::group(['middleware' => ['web', \App\Http\Middleware\SetAdminLocale::class, \App\Http\Middleware\SetAdminTheme::class]], function () {
    require __DIR__ . '/web/admin/auth.php';
    require __DIR__ . '/web/admin/dashboard.php';
    require __DIR__ . '/web/admin/categories.php';
    require __DIR__ . '/web/admin/admins.php';
    require __DIR__ . '/web/admin/users.php';
    require __DIR__ . '/web/admin/brands.php';
    require __DIR__ . '/web/admin/otps.php';
    
    // Theme switch route (available to both guests and logged-in admins)
    Route::get('admin/theme/{theme}', function ($theme) {
        $allowed = ['dark', 'light'];
        if (!in_array($theme, $allowed)) {
            abort(404);
        }
        session(['admin_theme' => $theme]);
        // 1 year in minutes
        $minutes = 60 * 24 * 365;
        return redirect()->back()->withCookie(cookie('admin_theme', $theme, $minutes));
    })->name('admin.theme.switch');
});
// Keep the default web root route
Route::get('/', function () {
    return view('welcome');
});
