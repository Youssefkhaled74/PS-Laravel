<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::post('auth/password/forgot', [\App\Http\Controllers\Api\User\Password\ForgotPasswordController::class, 'forgot'])->name('api.user.auth.password.forgot');
    Route::post('auth/password/verify-otp', [\App\Http\Controllers\Api\User\Password\ForgotPasswordController::class, 'verifyOtp'])->name('api.user.auth.password.verify_otp');
    Route::post('auth/password/reset', [\App\Http\Controllers\Api\User\Password\ResetPasswordController::class, 'reset'])->name('api.user.auth.password.reset');
});
