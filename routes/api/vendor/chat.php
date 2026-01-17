<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Chat\ConversationController as VendorConversationController;
use App\Http\Controllers\Api\V1\Vendor\Chat\MessageController as VendorMessageController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('conversations', [VendorConversationController::class, 'index']);
    Route::post('conversations', [VendorConversationController::class, 'store']);
    Route::get('conversations/{conversation}/messages', [VendorMessageController::class, 'index']);
    Route::post('conversations/{conversation}/messages', [VendorMessageController::class, 'store']);
    Route::post('conversations/{conversation}/read', [VendorConversationController::class, 'markRead']);
});
