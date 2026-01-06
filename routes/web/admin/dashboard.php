<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('admin/logout', [\App\Http\Controllers\Admin\Auth\AdminLoginController::class, 'logout'])->name('admin.logout');
});

