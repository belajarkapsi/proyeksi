<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\Pengelola;
use App\Models\User;
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
            'password.required' => 'Kolom Password Tidak Boleh Kosong!',
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

        if (Auth::guard('pengelola')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('pengelola.dashboard'));
        }

        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        }

        // Cek apakah akun ada di database atau tidak
        $username = $request->input('username');
        // cek pemilik
        $checkPemilik = Pemilik::where('username', $username)
            ->orWhere('email', $username)
            ->first();
        // cek pengelola
        $checkPengelola = Pengelola::where('username', $username)
            ->orWhere('email', $username)
            ->first();
        // cek penyewa
        $checkPenyewa = User::where('username', $username)
            ->orWhere('email', $username)
            ->first();

        // Jika di Pemilik TIDAK ADA dan di Web juga TIDAK ADA
        $userExists = $checkPemilik || $checkPengelola || $checkPenyewa;
        if (!$userExists) {
            return back()->withErrors([
                'username' => 'Username/Password tidak ditemukan! Akun belum terdaftar.',
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
        foreach (['pemilik', 'pengelola', 'web'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        // invalidate session dan regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('dashboard');
    }
}
