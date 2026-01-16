<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Onboarding\CommercialController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/commercial', [CommercialController::class, 'store']);
});
