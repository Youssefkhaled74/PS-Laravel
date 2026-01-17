<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\User\Chat\ConversationController;
use App\Http\Controllers\Api\V1\User\Chat\MessageController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('conversations', [ConversationController::class, 'index']);
    Route::post('conversations', [ConversationController::class, 'store']);
    Route::get('conversations/{conversation}/messages', [MessageController::class, 'index']);
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store']);
    Route::post('conversations/{conversation}/read', [ConversationController::class, 'markRead']);
});
