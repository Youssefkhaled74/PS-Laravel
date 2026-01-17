<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Notifications\UserNotificationController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [UserNotificationController::class, 'index']);
    Route::patch('/notifications/{id}/read', [UserNotificationController::class, 'markAsRead']);
    Route::patch('/notifications/read-all', [UserNotificationController::class, 'markAllAsRead']);
});
