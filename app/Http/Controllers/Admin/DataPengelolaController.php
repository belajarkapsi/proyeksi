<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengelola;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;

class DataPengelolaController extends Controller
{
    public function index()
    {
        $pengelolas = Pengelola::orderBy('id_pengelola', 'asc')->get();

        return view('admin.data-pengelola.index', compact('pengelolas'));
    }

    public function create()
    {
        return view('admin.data-pengelola.create');
    }

    public function store(Request $request)
    {
        // Validasi
        $validasiData = $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'no_telp'       => ['required', 'numeric'],
            'email'         => ['required', 'email', 'unique:pengelola,email'],
            'username'      => ['required', 'string', 'min:8', 'max:255', 'unique:pengelola,username'],
            'password'      => ['required', 'string', 'min:8'],
            'tanggal_lahir' => ['required', 'date', 'before:-10 years'],
            'usia'          => ['required', 'integer'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'foto_profil'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ],
            [
                'required'              => 'Data ini wajib diisi, jangan dikosongkan!',
                'numeric'               => 'Harus Berupa Angka!',
                'username.unique'       => 'Username sudah digunakan!',
                'email.unique'          => 'Email sudah digunakan!',
                'nama_lengkap.regex'    => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                'username.min'          => 'Username minimal memiliki panjang 8 karakter',
                'tanggal_lahir.before'  => 'Usia anda terlalu muda, minimal lebih dari 10 tahun.'
        ]);

        // Siapkan data yang akan disimpan
        $data =[
            'nama_lengkap'  => $validasiData['nama_lengkap'],
            'no_telp'       => $validasiData['no_telp'],
            'email'         => $validasiData['email'],
            'username'      => $validasiData['username'],
            'password'      => Hash::make($validasiData['password']),
            'tanggal_lahir' => $validasiData['tanggal_lahir'],
            'usia'          => $validasiData['usia'],
            'jenis_kelamin' => $validasiData['jenis_kelamin'],
        ];

        if($request->hasFile('foto_profil')) {
            $gambar = $request->file('foto_profil')->store('foto_profil', 'public');
            $data['foto_profil'] = $gambar;
        }

        // Simpan data
        Pengelola::create($data);

        Alert::success('Berhasil!', 'Data Pengelola Berhasil Ditambahkan!');
        return redirect()->route('pengelola.index');
    }

    public function show(string $id)
    {
        $pengelola = Pengelola::findOrFail($id);

        return view('admin.data-pengelola.show', compact('pengelola'));
    }

    public function edit(string $id)
    {
        $pengelola = Pengelola::findOrFail($id);

        return view('admin.data-pengelola.edit', compact('pengelola'));

    }

    public function update(Request $request, string $id)
    {
        $pengelola = Pengelola::findOrFail($id);

        // Validasi
        $validasiData = $request->validate([
            'nama_lengkap'  => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'no_telp'       => ['required', 'numeric'],
            'email'         => ['required', 'email', Rule::unique('pengelola', 'email')->ignore($pengelola->id_pengelola, 'id_pengelola')],
            'username'      => ['required', 'string', 'min:8', 'max:255', Rule::unique('pengelola', 'username')->ignore($pengelola->id_pengelola, 'id_pengelola')],
            'password'      => ['nullable', 'string', 'min:8'],
            'tanggal_lahir' => ['required', 'date', 'before:-10 years'],
            'usia'          => ['required', 'integer'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'foto_profil'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ],
            [
                'required'              => 'Data ini wajib diisi, jangan dikosongkan!',
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
            if ($pengelola->foto_profil && Storage::disk('public')->exists($pengelola->foto_profil)) {
                Storage::disk('public')->delete($pengelola->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $validasiData['foto_profil'] = $path;
        }

        // Update Data
        $pengelola->update($validasiData);

        Alert::success('Berhasil!', 'Data Pengelola Berhasil Diperbarui!');
        return redirect()->route('pengelola.index');
    }

    public function destroy(string $id)
    {
        $pengelola = Pengelola::findOrFail($id);

        if($pengelola->foto_profil && Storage::disk('public')->exists($pengelola->foto_profil)) {
            Storage::disk('public')->delete($pengelola->foto_profil);
        }

        $pengelola->delete();

        Alert::success('Berhasil!', 'Data Pengelola Berhasil Dihapus!');
        return redirect()->route('pengelola.index');
    }
}
