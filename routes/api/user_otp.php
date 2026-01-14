<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('auth/otp/send', [\App\Http\Controllers\Api\User\OTP\OtpController::class, 'send'])->name('api.user.auth.otp.send');
    Route::post('auth/otp/verify', [\App\Http\Controllers\Api\User\OTP\OtpController::class, 'verify'])->name('api.user.auth.otp.verify');
    Route::post('auth/otp/resend', [\App\Http\Controllers\Api\User\OTP\OtpController::class, 'resend'])->name('api.user.auth.otp.resend');
});
