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
        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ],
        [
            'username.required' => 'Kolom Username Tidak Boleh Kosong!',
            'password.required' => 'Kolom PasswordTidak Boleh Kosong!',
        ]

    );

        $credentials = [
            $login_type => $request->input('username'),
            'password'  => $request->input('password')
        ];

        if (Auth::guard('pemilik')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('admin.dashboard'));
        }

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Cek apakah akun ada di database atau tidak
        $checkPemilik = Auth::guard('pemilik')->getProvider()->retrieveByCredentials([
            $login_type => $request->input('username')
        ]);

        $checkWeb = Auth::guard('web')->getProvider()->retrieveByCredentials([
            $login_type => $request->input('username')
        ]);

        // Jika di Pemilik TIDAK ADA dan di Web juga TIDAK ADA
        if (!$checkPemilik && !$checkWeb) {
            return back()->withErrors([
                'username' => 'Username/password tidak ditemukan! Akun belum terdaftar.',
            ])->onlyInput('username');
        }

        return back()->withErrors([
            'username' => 'Username/Email atau Password Salah!',
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
