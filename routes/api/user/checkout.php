<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Checkout\CheckoutAddressController;
use App\Http\Controllers\Api\V1\User\Checkout\CheckoutPaymentController;
use App\Http\Controllers\Api\V1\User\Checkout\CheckoutSummaryController;
use App\Http\Controllers\Api\V1\User\Checkout\CheckoutConfirmController;

Route::prefix('v1')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('checkout/address', [CheckoutAddressController::class, 'store']);
        Route::post('checkout/payment', [CheckoutPaymentController::class, 'select']);
        Route::get('checkout/summary', [CheckoutSummaryController::class, 'show']);
        Route::post('checkout/confirm', [CheckoutConfirmController::class, 'store']);
    });

    // public
    Route::get('payment/methods', [CheckoutPaymentController::class, 'methods']);
});
