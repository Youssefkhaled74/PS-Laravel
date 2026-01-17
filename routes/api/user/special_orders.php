<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\SpecialOrders\LookupController;
use App\Http\Controllers\Api\V1\User\SpecialOrders\VendorBrowseController;
use App\Http\Controllers\Api\V1\User\SpecialOrders\SpecialOrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/special-orders/lookups', [LookupController::class, 'index']);
    Route::get('/special-orders/vendors', [VendorBrowseController::class, 'index']);
    Route::post('/special-orders', [SpecialOrderController::class, 'store']);
    Route::get('/special-orders', [SpecialOrderController::class, 'index']);
    Route::get('/special-orders/{id}', [SpecialOrderController::class, 'show']);
});
