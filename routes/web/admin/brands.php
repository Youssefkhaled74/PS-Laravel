<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BrandController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/brands', [BrandController::class, 'index'])->name('admin.brands.index');
    Route::get('admin/brands/create', [BrandController::class, 'create'])->name('admin.brands.create');
    Route::post('admin/brands', [BrandController::class, 'store'])->name('admin.brands.store');
    Route::get('admin/brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
    Route::put('admin/brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
    Route::patch('admin/brands/{brand}/toggle', [BrandController::class, 'toggle'])->name('admin.brands.toggle');
    Route::delete('admin/brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');
});
