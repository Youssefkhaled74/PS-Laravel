<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Cart\AddToCartController;
use App\Http\Controllers\Api\V1\User\Cart\CartController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart/items', [AddToCartController::class, 'store']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::patch('/cart/items/{id}', [CartController::class, 'update']);
    Route::delete('/cart/items/{id}', [CartController::class, 'destroy']);
});
