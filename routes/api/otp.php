<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\OtpController;

Route::post('auth/otp/send', [OtpController::class, 'send'])->name('api.auth.otp.send');
Route::post('auth/otp/verify', [OtpController::class, 'verify'])->name('api.auth.otp.verify');
