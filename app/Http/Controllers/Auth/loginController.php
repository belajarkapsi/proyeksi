<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // // 1. Cek apakah Credentials benar tertangkap
        // dd($credentials);

        // // 2. Cek apakah Guard Pemilik berhasil login?
        // if (Auth::guard('pemilik')->attempt($credentials)) {
        //     dd("LOGIN ADMIN BERHASIL! User: " . Auth::guard('pemilik')->user()->nama_lengkap);
        // } else {
        //     dd("LOGIN ADMIN GAGAL. Cek username/password atau config auth.");
        // }

        if (Auth::guard('pemilik')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username / Password Salah!',
        ])->onlyInput('username');
    }

    /**
     * Logout user safely.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Logout dari semua guard yang mungkin terpakai
        if (Auth::guard('pemilik')->check()) {
            Auth::guard('pemilik')->logout();
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        Auth::logout();

        // invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }
}
