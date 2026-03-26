<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // نمایش فرم لاگین
    public function showLoginForm()
    {
        return view('login');
    }

    // لاگین کاربر
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

       if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // بررسی بخش کاربر و ریدایرکت مناسب
            if ($user->section === 'Factory') {
                return redirect()->route('fa_dashboard.index'); // مسیر داشبورد کارخانه
            } elseif ($user->section === 'Trade_al') {
                return redirect()->route('al_dashboard.index'); // مسیر داشبورد تامین المونیم
            } 
        }


        return back()->withErrors([
            'email' => 'ایمیل یا رمز عبور اشتباه است.',
        ])->onlyInput('email');
    }

    // لاگ‌اوت کاربر
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
