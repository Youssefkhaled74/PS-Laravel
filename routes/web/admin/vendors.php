<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VendorPackageAssignmentController;

Route::middleware(['auth:admin'])->group(function () {
    Route::post('admin/vendors/{vendor}/package', [VendorPackageAssignmentController::class, 'assign'])->name('admin.vendors.package.update');
});
