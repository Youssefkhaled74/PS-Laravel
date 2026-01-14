<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('me', [\App\Http\Controllers\Api\User\Profile\MeController::class, 'me'])->name('api.user.me');
});
