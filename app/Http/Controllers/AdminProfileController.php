<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = Auth::guard('pemilik')->user();
        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('pemilik')->user();

        $validasiData = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'username'     => ['required', 'string', 'max:255', 'min:8', Rule::unique('pemilik')->ignore($admin->id_pemilik, 'id_pemilik')],
            'email'        => ['required', 'email', Rule::unique('pemilik')->ignore($admin->id_pemilik, 'id_pemilik')],
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
            if ($admin->foto_profil && Storage::disk('public')->exists($admin->foto_profil)) {
                Storage::disk('public')->delete($admin->foto_profil);
            }
            $validasiData['foto_profil'] = $request->file('foto_profil')->store('admin-photos', 'public');
        }

        $admin->update($validasiData);

        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
