<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorOnboardingController;

// Public vendor registration
Route::get('vendor/register', function(){ return view('vendor.register'); })->name('vendor.register.form');
Route::post('vendor/register', [VendorOnboardingController::class, 'register'])->name('vendor.register');

// Routes that require vendor auth (guard 'vendor')
Route::middleware(['auth:vendor'])->group(function () {
    Route::post('vendor/complete-business', [VendorOnboardingController::class, 'completeBusiness'])->name('vendor.complete.business');
    Route::post('vendor/choose-package', [VendorOnboardingController::class, 'choosePackage'])->name('vendor.choose.package');
    Route::post('vendor/choose-payment', [VendorOnboardingController::class, 'choosePayment'])->name('vendor.choose.payment');
});
