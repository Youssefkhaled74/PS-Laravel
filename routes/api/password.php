<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\PasswordController;

Route::post('auth/password/forgot', [PasswordController::class, 'forgot'])->name('api.auth.password.forgot');
Route::post('auth/password/reset', [PasswordController::class, 'reset'])->name('api.auth.password.reset');
