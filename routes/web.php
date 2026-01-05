<?php

use Illuminate\Support\Facades\Route;

// Web loader: include modular web route files (admin area, etc.)
Route::group(['middleware' => ['web', \App\Http\Middleware\SetAdminLocale::class]], function () {
    require __DIR__ . '/web/admin/auth.php';
    require __DIR__ . '/web/admin/dashboard.php';
    require __DIR__ . '/web/admin/categories.php';
});
// Keep the default web root route
Route::get('/', function () {
    return view('welcome');
});
