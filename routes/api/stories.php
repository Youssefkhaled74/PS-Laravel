<?php

use App\Http\Controllers\Api\UserStoriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| User Stories API Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('user/stories')->group(function () {
    // Get vendors with stories
    Route::get('/vendors', [UserStoriesController::class, 'getVendors']);
    
    // Get stories for a vendor
    Route::get('/vendor/{vendor}', [UserStoriesController::class, 'getVendorStories']);
    
    // Mark story as viewed
    Route::post('/{story}/view', [UserStoriesController::class, 'markStoryAsViewed']);
    
    // Mark all vendor stories as viewed
    Route::post('/vendor/{vendor}/mark-all-viewed', [UserStoriesController::class, 'markVendorStoriesAsViewed']);
});
