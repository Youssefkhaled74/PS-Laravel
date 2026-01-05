<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('admin/categories/{id}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('admin/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::patch('admin/categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('admin.categories.toggle');
    Route::delete('admin/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
});
