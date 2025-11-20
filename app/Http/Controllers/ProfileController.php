<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'no_telp' => ['required', 'numeric'],
            'username' => ['required', 'string', 'min:8', 'max:255', Rule::unique('penyewa')->ignore($penyewa->id_penyewa, 'id_penyewa')],
            'asal' => ['required', 'string'],
            'alamat' => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['required', 'date', 'before:-10 years'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ],
            [
                'reqired' => 'Data ini wajib diisi, jangan dikosongkan!',
                'numeric' => 'Harus Berupa Angka!',
                'unique' => 'Username sudah digunakan!',
                'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                'username.min' => 'Username minimal memiliki panjang 8 karakter',
                'tanggal_lahir.before' => 'Usia anda terlalu muda, minimal lebih dari 10 tahun.'
            ]);

        // Sanitasi Input
        $kolomInput = ['nama_lengkap', 'username', 'asal', 'alamat'];
        foreach ($kolomInput as $kolom) {
            if(isset($validasiData[$kolom])) {
                $validasiData[$kolom] = strip_tags($validasiData[$kolom]);
            }
        }

        // Hitung usia
        if($request->filled('tanggal_lahir')) {
            $validasiData['usia'] = \Carbon\Carbon::parse($request->tanggal_lahir)->age;
        }

        // Logika Upload Foto
        $pesan = 'Data profil berhasil diperbarui!';
        
        if ($request->hasFile('foto_profil')) {
            try{
                if($penyewa->foto_profil && Storage::disk('public')->exists($penyewa->foto_profil)) {
                    Storage::disk('public')->delete($penyewa->foto_profil);
                }
                // Simpan foto
                $path = $request->file('foto_profil')->store('profile-photos', 'public');
                $validasiData['foto_profil'] = $path;

            } catch (\Exception $e) {
                return back()->withErrors(['foto_profil' => 'Gagal merubah foto:    ' . $e->getMessage()])->withInput();
            }
        }

        // Simpan
        $penyewa->update($validasiData);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
