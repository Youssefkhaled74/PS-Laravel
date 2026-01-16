<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Onboarding\SubscriptionController;

// Public endpoint - anyone can view packages
Route::get('/', [SubscriptionController::class, 'packages']);

// Protected endpoint - select package
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/select', [SubscriptionController::class, 'select']);
});
