<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\VendorPackageAssignmentController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\VendorStoriesController;
use App\Http\Controllers\Admin\StoryController;

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

    // Vendor Stories routes (vendor-specific)
    Route::prefix('admin/vendors/{vendor}/stories')->name('admin.vendors.stories.')->group(function () {
        Route::get('/', [VendorStoriesController::class, 'index'])->name('index');
        Route::get('/create', [VendorStoriesController::class, 'create'])->name('create');
        Route::post('/', [VendorStoriesController::class, 'store'])->name('store');
        Route::get('/{story}/edit', [VendorStoriesController::class, 'edit'])->name('edit');
        Route::put('/{story}', [VendorStoriesController::class, 'update'])->name('update');
        Route::delete('/{story}', [VendorStoriesController::class, 'destroy'])->name('destroy');
        Route::patch('/{story}/toggle', [VendorStoriesController::class, 'toggleStatus'])->name('toggle');
    });

    // Global Stories routes (all vendors)
    Route::prefix('admin/stories')->name('admin.stories.')->group(function () {
        Route::get('/', [StoryController::class, 'index'])->name('index');
        Route::get('/create', [StoryController::class, 'create'])->name('create');
        Route::post('/', [StoryController::class, 'store'])->name('store');
        Route::get('/{story}/edit', [StoryController::class, 'edit'])->name('edit');
        Route::put('/{story}', [StoryController::class, 'update'])->name('update');
        Route::delete('/{story}', [StoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{story}/toggle', [StoryController::class, 'toggleStatus'])->name('toggle');
    });
});
