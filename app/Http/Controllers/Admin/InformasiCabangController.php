<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class InformasiCabangController extends Controller
{
    public function index($lokasi)
    {
        $cabangs = Cabang::where('lokasi', $lokasi)->get();

        return view('admin.informasi-cabang.index', compact('cabangs', 'lokasi'));
    }

    public function edit($lokasi, $id)
    {
        $cabang = Cabang::findOrFail($id);

        return view('admin.informasi-cabang.edit', compact('cabang', 'lokasi'));
    }

    public function update(Request $request, $lokasi, $id)
    {
        $cabang = Cabang::findOrFail($id);

        // Validasi
        $validasi = $request->validate([
            'nama_cabang'       => ['required', 'string', 'max:255'],
            'deskripsi'         => ['required', 'string'],
            'jumlah_kamar'      => ['required', 'integer'],
            'lokasi'            => ['required', 'string', 'max:255'],
            'kategori_cabang'   => ['required', 'in:kost,villa'],
            'gambar_cabang'     => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048']
        ]);

        // Handle Upload Gambar Cabang
        if ($request->hasFile('gambar_cabang')) {
            // Hapus gambar lama jika ada (dan bukan dummy)
            if ($cabang->gambar_cabang && $cabang->gambar_cabang != 'dummy.jpg' && Storage::disk('public')->exists($cabang->gambar_cabang)) {
                Storage::disk('public')->delete($cabang->gambar_cabang);
            }

            // Simpan gambar baru
            $path = $request->file('gambar_cabang')->store('cabang-images', 'public');
            $validasi['gambar_cabang'] = $path;
        }

        // Simpan Perubahan
        $cabang->update($validasi);

        Alert::success('Berhasil!', 'Informasi Cabang Berhasil Diperbarui!');
        return redirect()->route('admin.cabanginformasi-cabang.index', $lokasi);
    }

    public function destroy($lokasi, $id)
    {
        $cabang = Cabang::findOrFail($id);
        // Hapus gambar jika ada
        if ($cabang->gambar_cabang && $cabang->gambar_cabang != 'dummy.jpg' && Storage::disk('public')->exists($cabang->gambar_cabang)) {
            Storage::disk('public')->delete($cabang->gambar_cabang);
        }
        $cabang->delete();

        Alert::success('Berhasil!', 'Cabang Berhasil Dihapus!');
        return redirect()->route('admin.cabanginformasi-cabang.index', $lokasi);
    }
}
