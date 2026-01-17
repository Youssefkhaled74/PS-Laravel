<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Auth\VendorOtpController;

Route::post('/send', [VendorOtpController::class, 'send']);
Route::post('/verify', [VendorOtpController::class, 'verify']);
Route::post('/resend', [VendorOtpController::class, 'resend']);

// Password reset flows (vendor)
Route::post('/password/forgot', [\App\Http\Controllers\Api\V1\Vendor\Password\ForgotPasswordController::class, 'forgot']);
Route::post('/password/verify-otp', [\App\Http\Controllers\Api\V1\Vendor\Password\ForgotPasswordController::class, 'verifyOtp']);
Route::post('/password/reset', [\App\Http\Controllers\Api\V1\Vendor\Password\ForgotPasswordController::class, 'reset']);
