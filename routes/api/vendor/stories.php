<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Stories\VendorStoriesController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/stories', [VendorStoriesController::class, 'index']);
    Route::post('/stories', [VendorStoriesController::class, 'store']);
    Route::post('/stories/{story}/toggle', [VendorStoriesController::class, 'toggle']);
    Route::get('/stories/{story}/analytics', [VendorStoriesController::class, 'analytics']);
    Route::put('/stories/{story}', [VendorStoriesController::class, 'update']);
    Route::delete('/stories/{story}', [VendorStoriesController::class, 'destroy']);
});
