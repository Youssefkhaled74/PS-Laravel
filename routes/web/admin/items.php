<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Items\ItemController;
use App\Http\Controllers\Admin\Items\ItemApprovalController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/items', [ItemController::class, 'index'])->name('admin.items.index');
    Route::get('admin/items/{item}', [ItemController::class, 'show'])->name('admin.items.show');

    Route::patch('admin/items/{item}/approve', [ItemApprovalController::class, 'approve'])->name('admin.items.approve');
    Route::patch('admin/items/{item}/reject', [ItemApprovalController::class, 'reject'])->name('admin.items.reject');
});
