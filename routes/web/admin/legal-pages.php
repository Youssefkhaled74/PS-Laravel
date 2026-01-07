<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LegalPageController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/legal-pages', [LegalPageController::class, 'index'])->name('admin.legal-pages.index');
    Route::get('admin/legal-pages/{key}/edit', [LegalPageController::class, 'edit'])->name('admin.legal-pages.edit');
    Route::put('admin/legal-pages/{key}', [LegalPageController::class, 'update'])->name('admin.legal-pages.update');
    Route::get('admin/legal-pages/{key}/preview', [LegalPageController::class, 'preview'])->name('admin.legal-pages.preview');
});
