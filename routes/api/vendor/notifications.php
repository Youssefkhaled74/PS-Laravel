<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Notifications\NotificationController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('notifications', [NotificationController::class, 'index']);
});
