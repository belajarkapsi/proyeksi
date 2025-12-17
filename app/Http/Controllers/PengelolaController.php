<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

// use Illuminate\Http\Request;

class PengelolaController extends Controller
{
    public function index()
    {
        $pengelola = Auth::guard('pengelola')->user();
        $cabang = $pengelola->cabang;

        return view('pengelola.dashboard', compact('pengelola', 'cabang'));
    }

    public function edit()
    {
        $pengelola = Auth::guard('pengelola')->user();
        return view('pengelola.profile', compact('pengelola'));
    }

    public function update(Request $request)
    {
        $pengelola = Auth::guard('pengelola')->user();

        $validasiData = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'username'     => ['required', 'string', 'max:255', 'min:8', Rule::unique('pengelola')->ignore($pengelola->id_pengelola, 'id_pengelola')],
            'email'        => ['required', 'email', Rule::unique('pengelola')->ignore($pengelola->id_pengelola, 'id_pengelola')],
            'no_telp'      => ['nullable', 'numeric'],
            'foto_profil'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ],
        [
                'required' => 'Data ini wajib diisi, jangan dikosongkan!',
                'numeric' => 'Harus Berupa Angka!',
                'username.unique' => 'Username sudah ada yang gunakan!',
                'email.unique' => 'Email sudah ada yang gunakan!',
                'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                'username.min' => 'Username minimal memiliki panjang 8 karakter',
                'image' => 'Gambar harus berformat: jpg, jpeg, atau png!',
                'foto_profil.max' => 'Ukuran gambar maks: 2mb!'
            ]);

        // Sanitasi Input
        $kolomInput = ['nama_lengkap', 'username', 'email'];
        foreach ($kolomInput as $kolom) {
            if(isset($validasiData[$kolom])) {
                $validasiData[$kolom] = strip_tags($validasiData[$kolom]);
            }
        }

        if ($request->hasFile('foto_profil')) {
            if ($pengelola->foto_profil && Storage::disk('public')->exists($pengelola->foto_profil)) {
                Storage::disk('public')->delete($pengelola->foto_profil);
            }
            $validasiData['foto_profil'] = $request->file('foto_profil')->store('pengelola-photos', 'public');
        }

        $pengelola->update($validasiData);

        return redirect()->route('pengelola.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
