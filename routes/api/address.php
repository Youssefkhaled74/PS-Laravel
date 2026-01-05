<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Adresses\AddressController;

// Public/protected separation: the address management endpoints require auth:sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('auth/logout', [\App\Http\Controllers\Api\Auth\LogoutController::class, 'logout'])->name('api.auth.logout');

    Route::get('me/addresses', [AddressController::class, 'index'])->name('api.addresses.index');
    Route::post('me/addresses', [AddressController::class, 'store'])->name('api.addresses.store');
    Route::get('me/addresses/{id}', [AddressController::class, 'show'])->name('api.addresses.show');
    Route::patch('me/addresses/{id}', [AddressController::class, 'update'])->name('api.addresses.update');
    Route::delete('me/addresses/{id}', [AddressController::class, 'destroy'])->name('api.addresses.destroy');
    Route::patch('me/addresses/{id}/default', [AddressController::class, 'setDefault'])->name('api.addresses.set_default');
});
