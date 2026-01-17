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
    require __DIR__ . '/web/admin/banks.php';
    require __DIR__ . '/web/admin/vendor-packages.php';
    require __DIR__ . '/web/admin/otps.php';
    require __DIR__ . '/web/admin/vendors.php';
    require __DIR__ . '/web/admin/items.php';
    require __DIR__ . '/web/admin/legal-pages.php';
    // vendor onboarding (public + vendor-guarded)
    require __DIR__ . '/web/vendor_onboarding.php';
    
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
// Keep the default web root route (landing page)
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Language switch (sets session locale and redirects back)
Route::get('/lang/{locale}', function ($locale) {
    $allowed = ['en', 'ar'];
    if (!in_array($locale, $allowed)) {
        abort(404);
    }
    session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');

// Legal pages (stubs - could be loaded from DB via controller)
Route::get('/terms', function () {
    return view('legal.stub', ['title' => 'Terms & Conditions', 'key' => 'terms']);
})->name('legal.terms');

Route::get('/privacy', function () {
    return view('legal.stub', ['title' => 'Privacy Policy', 'key' => 'privacy']);
})->name('legal.privacy');

// Contact form POST (simple handling)
use Illuminate\Http\Request;
Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:191',
        'email' => 'required|email|max:191',
        'phone' => 'nullable|string|max:50',
        'message' => 'required|string|max:2000',
    ]);
    // For now, we won't persist. You can hook this to a model/controller later.
    // Flash success and redirect back
    return redirect()->back()->with('success', __('landing.contact_success'));
})->name('contact.submit');
