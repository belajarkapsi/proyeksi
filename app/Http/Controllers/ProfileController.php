<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    // Proses Menampilkan halaman edit 
    public function edit()
    {
        $penyewa = Auth::user();
        return view('profile.edit', compact('penyewa'));
    }

    // Update data
    public function update(Request $request)
    {
        $penyewa = Auth::user();

        // Validasi dulu
        $validasiData = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'no_telp' => ['required', 'numeric'],
            'email' => ['required', 'email', Rule::unique('penyewa')->ignore($penyewa, 'id_penyewa')],
            'username' => ['required', 'string', 'max:255'],
        ]);

        // Simpan
        $penyewa->update($validasiData);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
