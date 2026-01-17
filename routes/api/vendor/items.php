<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Items\LookupController;
use App\Http\Controllers\Api\V1\Vendor\Items\ItemController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/items/lookups', [LookupController::class, 'index']);
    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::get('/items/{id}', [ItemController::class, 'show']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);
});
