<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Favorites\FavoritesController;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('favorites', [FavoritesController::class, 'index']);
    Route::post('favorites/toggle', [FavoritesController::class, 'toggle']);
    Route::get('favorites/count', [FavoritesController::class, 'count']);
});
