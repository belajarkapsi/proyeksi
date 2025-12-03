<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class DataKamarController extends Controller
{
    private function getCabang($lokasi)
    {
        $cabang = Cabang::where('lokasi', $lokasi)->firstOrFail();
        return $cabang;
    }

    public function index($lokasi)
    {
        // 1. Ambil SEMUA data cabang di lokasi tersebut (Contoh: Parepare Kost & Parepare Villa)
        $daftarCabang = Cabang::where('lokasi', $lokasi)->get();

        // 2. Ambil ID-nya saja. Hasilnya array, misal: [1, 2]
        $arrayIdCabang = $daftarCabang->pluck('id_cabang');

        // 3. Query Kamar menggunakan 'whereIn' (dimana id_cabang ada di dalam array [1, 2])
        $kamars = Kamar::with('cabang')
                    ->whereIn('id_cabang', $arrayIdCabang)
                    ->orderBy('no_kamar', 'asc')
                    ->paginate(15);

        $cabang = $daftarCabang->first();

        return view('admin.data-kamar.index', compact('cabang', 'lokasi', 'kamars'));
    }

    public function create($lokasi)
    {
        return view('admin.data-kamar.create', compact('lokasi'));
    }

    public function store(Request $request, $lokasi)
    {
        $cabang = $this->getCabang($lokasi);

        // Validasi input
        $validasi = $request->validate([
            'no_kamar'      => ['required', 'max:10'],
            'tipe_kamar'    => ['required', 'in:Ekonomis,Standar'],
            'harga_kamar'   => ['required', 'numeric'],
            'deskripsi'     => ['required'],
            'status'        => ['required', 'in:Tersedia,Dihuni'],
            'gambar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // 2. Handle Upload Gambar
        if ($request->hasFile('gambar')) {
            // Simpan ke folder 'public/kamar-images'
            $path = $request->file('gambar')->store('kamar-images', 'public');
            $validasi['gambar'] = $path;
        }

        $validasi['id_cabang'] = $cabang->id_cabang;

        // Simpan Data
        Kamar::create($validasi);

        Alert::success('Berhasil!', 'Data Kamar Berhasil Ditambahkan!');
        return redirect()->route('admin.cabangkamar.index', $lokasi);
    }

    public function edit($lokasi, $id)
    {
        // Cari kamar berdasarkan id
        $kamar = Kamar::findOrFail($id);
        return view('admin.data-kamar.edit', compact('kamar', 'lokasi'));
    }

    public function update(Request $request, $lokasi, $id)
    {
        $kamar = Kamar::findOrFail($id);

        // Validasi
        $validasi = $request->validate([
            'no_kamar'      => ['required', 'max:10'],
            'tipe_kamar'    => ['required', 'in:Ekonomis,Standar'],
            'harga_kamar'   => ['required', 'numeric'],
            'deskripsi'     => ['required'],
            'status'        => ['required', 'in:Tersedia,Dihuni'],
            'gambar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // Cek apakah foto profil diubah atau tidak
        if($request->hasFile('gambar')) {
            // Hapus foto lama jika ada (dan bukan default)
            if ($kamar->gambar && Storage::disk('public')->exists($kamar->gambar)) {
                Storage::disk('public')->delete($kamar->gambar);
            }

            // Simpan foto baru
            $path = $request->file('gambar')->store('kamar-images', 'public');
            $validasi['gambar'] = $path;
        }

        $kamar->update($validasi);

        Alert::success('Berhasil!', 'Data Kamar Berhasil Diperbarui!');
        return redirect()->route('admin.cabangkamar.index', $lokasi);
    }

    public function destroy($lokasi, $id)
    {
        $kamar = Kamar::findOrFail($id);
        $kamar->delete();

        Alert::success('Berhasil!', 'Data Kamar Berhasil Dihapus!');
        return redirect()->route('admin.cabangkamar.index', $lokasi);
    }
}
