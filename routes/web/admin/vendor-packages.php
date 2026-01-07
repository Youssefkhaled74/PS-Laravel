<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VendorPackageController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/vendor-packages', [VendorPackageController::class, 'index'])->name('admin.vendor-packages.index');
    Route::get('admin/vendor-packages/create', [VendorPackageController::class, 'create'])->name('admin.vendor-packages.create');
    Route::post('admin/vendor-packages', [VendorPackageController::class, 'store'])->name('admin.vendor-packages.store');
    Route::get('admin/vendor-packages/{vendorPackage}/edit', [VendorPackageController::class, 'edit'])->name('admin.vendor-packages.edit');
    Route::put('admin/vendor-packages/{vendorPackage}', [VendorPackageController::class, 'update'])->name('admin.vendor-packages.update');
    Route::patch('admin/vendor-packages/{vendorPackage}/toggle', [VendorPackageController::class, 'toggle'])->name('admin.vendor-packages.toggle');
    Route::delete('admin/vendor-packages/{vendorPackage}', [VendorPackageController::class, 'destroy'])->name('admin.vendor-packages.destroy');
});
