<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VendorPackageAssignmentController;
use App\Http\Controllers\Admin\VendorController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/vendors', [VendorController::class, 'index'])->name('admin.vendors.index');
    Route::get('admin/vendors/{vendor}', [VendorController::class, 'show'])->name('admin.vendors.show');

    Route::patch('admin/vendors/{vendor}/account', [VendorController::class, 'updateAccount'])->name('admin.vendors.updateAccount');
    Route::patch('admin/vendors/{vendor}/business', [VendorController::class, 'updateBusiness'])->name('admin.vendors.updateBusiness');
    Route::post('admin/vendors/{vendor}/documents', [VendorController::class, 'updateDocuments'])->name('admin.vendors.updateDocuments');

    // package assignment uses separate controller
    Route::post('admin/vendors/{vendor}/package', [VendorPackageAssignmentController::class, 'assign'])->name('admin.vendors.package.update');
    // toggle route (optional)
    Route::patch('admin/vendors/{vendor}/toggle', [VendorController::class, 'toggleStatus'])->name('admin.vendors.toggle');
});
