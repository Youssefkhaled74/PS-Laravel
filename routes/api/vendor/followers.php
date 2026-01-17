<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Followers\VendorFollowersController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/followers', [VendorFollowersController::class, 'list']);
    Route::get('/followers/count', [VendorFollowersController::class, 'count']);
});
