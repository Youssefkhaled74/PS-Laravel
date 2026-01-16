<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Onboarding\PaymentController;

// Public endpoint - anyone can view payment methods
Route::get('/methods', [PaymentController::class, 'methods']);

// Protected endpoint - confirm payment
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/confirm', [PaymentController::class, 'confirm']);
});
