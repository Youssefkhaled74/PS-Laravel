<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OtpController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/otps', [OtpController::class, 'index'])->name('admin.otps.index');
    Route::get('admin/otps/{id}', [OtpController::class, 'show'])->name('admin.otps.show');
    Route::patch('admin/otps/{id}/revoke', [OtpController::class, 'revoke'])->name('admin.otps.revoke');
    Route::delete('admin/otps/{id}', [OtpController::class, 'destroy'])->name('admin.otps.destroy');
    Route::post('admin/otps/{id}/resend', [OtpController::class, 'resend'])->name('admin.otps.resend');
});