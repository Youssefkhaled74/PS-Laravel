<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\SpecialOrders\SpecialOrderController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/special-orders', [SpecialOrderController::class, 'index']);
    Route::get('/special-orders/{id}', [SpecialOrderController::class, 'show']);
    Route::post('/special-orders/{id}/decision', [SpecialOrderController::class, 'decision']);
});
