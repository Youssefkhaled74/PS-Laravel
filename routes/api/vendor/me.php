<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Profile\MeController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [MeController::class, 'me']);
});