<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Shipping\ShippingDetailsController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/shipping-details', [ShippingDetailsController::class, 'index']);
    Route::post('/shipping-details', [ShippingDetailsController::class, 'store']);
});
