<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminManagementController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/admins', [AdminManagementController::class, 'index'])->name('admin.admins.index');
    Route::get('admin/admins/create', [AdminManagementController::class, 'create'])->name('admin.admins.create');
    Route::post('admin/admins', [AdminManagementController::class, 'store'])->name('admin.admins.store');
    Route::get('admin/admins/{id}/edit', [AdminManagementController::class, 'edit'])->name('admin.admins.edit');
    Route::put('admin/admins/{id}', [AdminManagementController::class, 'update'])->name('admin.admins.update');
    Route::patch('admin/admins/{id}/toggle-status', [AdminManagementController::class, 'toggleStatus'])->name('admin.admins.toggle');
    Route::delete('admin/admins/{id}', [AdminManagementController::class, 'destroy'])->name('admin.admins.destroy');
});
