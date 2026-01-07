<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BankController;

Route::middleware(['auth:admin'])->group(function () {
    Route::get('admin/banks', [BankController::class, 'index'])->name('admin.banks.index');
    Route::get('admin/banks/create', [BankController::class, 'create'])->name('admin.banks.create');
    Route::post('admin/banks', [BankController::class, 'store'])->name('admin.banks.store');
    Route::get('admin/banks/{bank}/edit', [BankController::class, 'edit'])->name('admin.banks.edit');
    Route::put('admin/banks/{bank}', [BankController::class, 'update'])->name('admin.banks.update');
    Route::patch('admin/banks/{bank}/toggle', [BankController::class, 'toggle'])->name('admin.banks.toggle');
    Route::delete('admin/banks/{bank}', [BankController::class, 'destroy'])->name('admin.banks.destroy');
});
