<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::get('addresses', [\App\Http\Controllers\Api\User\Address\AddressController::class, 'index'])->name('api.user.addresses.index');
    Route::post('addresses', [\App\Http\Controllers\Api\User\Address\AddressController::class, 'store'])->name('api.user.addresses.store');
    Route::put('addresses/{id}', [\App\Http\Controllers\Api\User\Address\AddressController::class, 'update'])->name('api.user.addresses.update');
    Route::delete('addresses/{id}', [\App\Http\Controllers\Api\User\Address\AddressController::class, 'destroy'])->name('api.user.addresses.destroy');
    Route::patch('addresses/{id}/default', [\App\Http\Controllers\Api\User\Address\AddressController::class, 'setDefault'])->name('api.user.addresses.set_default');
});
