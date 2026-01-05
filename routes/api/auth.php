<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;

Route::post('auth/register', [RegisterController::class, 'register'])->name('api.auth.register');
Route::post('auth/login', [LoginController::class, 'login'])->name('api.auth.login');

// Social placeholders (kept minimal)
Route::post('auth/social/google', function () {
    return response()->json(['success' => false, 'message' => __('api.not_implemented'), 'data' => null, 'errors' => null, 'meta' => null], 501);
});
Route::post('auth/social/apple', function () {
    return response()->json(['success' => false, 'message' => __('api.not_implemented'), 'data' => null, 'errors' => null, 'meta' => null], 501);
});
