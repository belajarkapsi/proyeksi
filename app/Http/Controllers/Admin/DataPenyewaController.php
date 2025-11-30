<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class DataPenyewaController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id_penyewa', 'desc')->paginate(10);

        return view('admin.data-penyewa.index', compact('users'));
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.data-penyewa.show', compact('user'));
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        return view('admin.data-penyewa.edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        // Validasi
        $validasiData = $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'no_telp'       => ['required', 'numeric'],
            'email'         => ['required', 'email', 'unique:penyewa,email,' . $user->id_penyewa . ',id_penyewa'],
            'username'      => ['required', 'string', 'min:8', 'max:255', Rule::unique('penyewa')->ignore($user->id_penyewa, 'id_penyewa')],
            'password'      => ['nullable', 'string', 'min:8'],
            'asal'          => ['nullable', 'string', 'max:100'],
            'alamat'        => ['nullable', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'tanggal_lahir' => ['nullable', 'date', 'before:-10 years'],
            'usia'          => ['nullable', 'integer'],
            'foto_profil'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ],
            [
                'required'               => 'Data ini wajib diisi, jangan dikosongkan!',
                'numeric'               => 'Harus Berupa Angka!',
                'username.unique'       => 'Username sudah digunakan!',
                'email.unique'          => 'Email sudah digunakan!',
                'nama_lengkap.regex'    => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                'username.min'          => 'Username minimal memiliki panjang 8 karakter',
                'tanggal_lahir.before'  => 'Usia anda terlalu muda, minimal lebih dari 10 tahun.'
        ]);

        // Cek Password apakah diubah atau tidak
        if($request->filled('password')) {
            $validasiData['password'] = Hash::make($request->password);
        } else{
            unset($validasiData['password']);
        }

        // Cek apakah foto profil diubah atau tidak
        if($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada (dan bukan default)
            if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
                Storage::disk('public')->delete($user->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $validasiData['foto_profil'] = $path;
        }

        // Update Data
        $user->update($validasiData);

        Alert::success('Berhasil!', 'Data Penyewa Berhasil Diperbarui!');
        return redirect()->route('penyewa.index');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->delete();

        Alert::success('Berhasil!', 'Data Penyewa Berhasil Dihapus!');
        return redirect()->route('penyewa.index');
    }
}
