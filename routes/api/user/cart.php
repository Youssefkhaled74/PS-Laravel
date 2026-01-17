<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Cart\AddToCartController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/items', [AddToCartController::class, 'store']);
});
