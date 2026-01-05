<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminLoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $lang = $request->get('lang', session('admin_locale', 'ar'));
        if (in_array($lang, ['ar','en'])) {
            session(['admin_locale' => $lang]);
        }
        app()->setLocale(session('admin_locale', 'ar'));
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required','email'],
            'password' => ['required','string'],
        ]);

        $admin = Admin::where('email', $data['email'])->first();

        if (! $admin || ! Hash::check($data['password'], $admin->password)) {
            return back()->withErrors(['email' => __('admin.flash.login_failed')])->withInput();
        }

        Auth::guard('admin')->login($admin, $request->boolean('remember'));

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('status', __('admin.flash.logged_out'));
        return redirect()->route('admin.login');
    }
}
