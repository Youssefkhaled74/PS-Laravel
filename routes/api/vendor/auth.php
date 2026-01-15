<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Auth\VendorRegisterController;
use App\Http\Controllers\Api\V1\Vendor\Auth\VendorLoginController;
use App\Http\Controllers\Api\V1\Vendor\Auth\VendorLogoutController;

Route::post('/register', [VendorRegisterController::class, 'register']);
Route::post('/login', [VendorLoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [VendorLogoutController::class, 'logout']);
});
