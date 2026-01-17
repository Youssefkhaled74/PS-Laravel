<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Follow\FollowVendorController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/follows', [FollowVendorController::class, 'follow']);
    Route::delete('/follows/{vendorId}', [FollowVendorController::class, 'unfollow']);
    Route::post('/follows/toggle', [FollowVendorController::class, 'toggle']);
    Route::get('/follows', [FollowVendorController::class, 'list']);
});
