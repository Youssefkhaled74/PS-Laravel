<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\OtpController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Adresses\AddressController;
use App\Http\Controllers\Api\Auth\LogoutController;


Route::middleware(['api'])->group(function () {
    // Auth routes
    Route::post('auth/register', [RegisterController::class, 'register']);
    Route::post('auth/login', [LoginController::class, 'login']);
    Route::post('auth/otp/send', [OtpController::class, 'send']);
    Route::post('auth/otp/verify', [OtpController::class, 'verify']);
    Route::post('auth/password/forgot', [PasswordController::class, 'forgot']);
    Route::post('auth/password/reset', [PasswordController::class, 'reset']);

    // placeholders for social
    Route::post('auth/social/google', function () { return response()->json(['success'=>false,'message'=>__('api.not_implemented'),'data'=>null,'errors'=>null,'meta'=>null],501); });
    Route::post('auth/social/apple', function () { return response()->json(['success'=>false,'message'=>__('api.not_implemented'),'data'=>null,'errors'=>null,'meta'=>null],501); });
    
    // Authenticated routes for managing addresses
    Route::middleware(['auth:sanctum'])->group(function () {
        // logout
        Route::post('auth/logout', [LogoutController::class, 'logout']);

        Route::get('me/addresses', [AddressController::class, 'index']);
        Route::post('me/addresses', [AddressController::class, 'store']);
        Route::get('me/addresses/{id}', [AddressController::class, 'show']);
        Route::patch('me/addresses/{id}', [AddressController::class, 'update']);
        Route::delete('me/addresses/{id}', [AddressController::class, 'destroy']);
        Route::patch('me/addresses/{id}/default', [AddressController::class, 'setDefault']);
    });
});
