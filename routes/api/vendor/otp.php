<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Vendor\Auth\VendorOtpController;

Route::post('/send', [VendorOtpController::class, 'send']);
Route::post('/verify', [VendorOtpController::class, 'verify']);
Route::post('/resend', [VendorOtpController::class, 'resend']);
