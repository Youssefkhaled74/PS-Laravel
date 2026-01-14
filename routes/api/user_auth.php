<?php

use Illuminate\Support\Facades\Route;

// Public user auth endpoints
Route::prefix('user')->group(function () {
    Route::post('auth/register', [\App\Http\Controllers\Api\User\Auth\RegisterController::class, 'register'])->name('api.user.auth.register');
    Route::post('auth/login', [\App\Http\Controllers\Api\User\Auth\LoginController::class, 'login'])->name('api.user.auth.login');

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [\App\Http\Controllers\Api\User\Auth\LogoutController::class, 'logout'])->name('api.user.auth.logout');
    });
});
