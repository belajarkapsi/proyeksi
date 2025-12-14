<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use illuminate\Support\Facades\Auth;
use illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Handle an registration.
     */
    public function store(Request $request)
    {
        //validasi data yang terinput dari form dengan pesan errornya
        $rules = [
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'numeric'],
            'email' => ['required', 'email', 'unique:penyewa,email'],
            'username' => ['required', 'string', 'max:255', 'unique:penyewa'],
            'password' => ['required', 'min:8', 'confirmed'],
        ];

        $message = [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'no_telp.required'      => 'Nomor telepon jangan lupa diisi.',
            'no_telp.numeric'       => 'Nomor telepon harus berupa angka.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email sepertinya salah, coba periksa lagi.',
            'email.unique'          => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'username.unique'       => 'Username ini sudah ada yang pakai, coba yang lain.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal harus 8 karakter agar aman.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok dengan password yang dimasukkan.',
        ];

        $validasi_data = $request->validate($rules, $message);

        //Simpan data penyewa kedalam database
        $penyewa = User::create([
            'nama_lengkap' => $validasi_data['nama_lengkap'],
            'no_telp' => $validasi_data['no_telp'],
            'email' => $validasi_data['email'],
            'username' => $validasi_data['username'],
            'password' => $validasi_data['password'],
            'role' => 'penyewa'
        ]);

        return redirect()->route('login')->with('status', 'Pendaftaran berhasil! Silakan login dengan akun Anda.');
    }
}
