<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VendorPackageAssignmentController;
use App\Http\Controllers\Admin\VendorController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/vendors', [VendorController::class, 'index'])->name('admin.vendors.index');
    Route::get('admin/vendors/{vendor}', [VendorController::class, 'show'])->name('admin.vendors.show');

    Route::post('admin/vendors/{vendor}/package', [VendorPackageAssignmentController::class, 'assign'])->name('admin.vendors.package.update');
});
