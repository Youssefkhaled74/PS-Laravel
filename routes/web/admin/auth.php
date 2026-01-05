<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminLoginController;

Route::get('admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');

// language switch for admin area
Route::get('admin/lang/{lang}', function ($lang) {
	if (! in_array($lang, ['ar','en'])) abort(404);
	session(['admin_locale' => $lang]);
	return redirect()->back();
})->name('admin.lang');
