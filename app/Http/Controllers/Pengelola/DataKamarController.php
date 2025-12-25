<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\PemesananItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class DataKamarController extends Controller
{
    /**
     * Ambil cabang milik pengelola login
     */
    private function cabang()
    {
        return auth('pengelola')->user()->cabang;
    }

    /**
     * Tampilkan data kamar (hanya cabang sendiri)
     */
    public function index()
    {
        $kamars = Kamar::where('id_cabang', $this->cabang()->id_cabang)
            ->orderBy('no_kamar', 'asc')
            ->get()
            ->map(function ($kamar) {
                $kamar->nama_penyewa = null;

                if ($kamar->status === 'Dihuni') {
                    $itemTerakhir = PemesananItem::where('id_kamar', $kamar->id_kamar)
                        ->whereHas('pemesanan', function($q) {
                            $q->where('status', 'Lunas');
                        })
                        ->latest('created_at')
                        ->with('pemesanan.penyewa')
                        ->first();

                    if ($itemTerakhir && $itemTerakhir->pemesanan && $itemTerakhir->pemesanan->penyewa) {
                        $kamar->nama_penyewa = $itemTerakhir->pemesanan->penyewa->nama_lengkap ?? $itemTerakhir->pemesanan->penyewa->username;
                    }
                }
                return $kamar;
            });

        return view('pengelola.data-kamar.index', compact('kamars'));
    }

    /**
     * Form tambah kamar
     */
    public function create()
    {
        return view('pengelola.data-kamar.create');
    }

    /**
     * Simpan kamar baru
     */
    public function store(Request $request, Kamar $kamar)
    {
        Gate::authorize('create', $kamar);

        $validasi = $request->validate([
            'no_kamar'      => ['required', 'max:10'],
            'tipe_kamar'    => ['required', 'in:Ekonomis,Standar'],
            'harga_kamar'   => ['required', 'numeric'],
            'deskripsi'     => ['required'],
            'status'        => ['required', 'in:Tersedia,Dihuni'],
            'gambar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            $validasi['gambar'] = $request->file('gambar')
                ->store('kamar-images', 'public');
        }

        $validasi['id_cabang'] = $this->cabang()->id_cabang;

        $kamar->create($validasi);

        Alert::success('Berhasil!', 'Data kamar berhasil ditambahkan.');
        return redirect()->route('pengelola.kamar.index');
    }

    public function show(Kamar $kamar)
    {
        Gate::authorize('view', $kamar);

        // Inisiasi variabel
        $penyewa = null;
        $pemesanan = null;
        $durasi = null;

        if($kamar->status === 'Dihuni') {
            $itemTerakhir = PemesananItem::where('id_kamar', $kamar->id_kamar)
                ->whereHas('pemesanan', function($q) {
                    $q->where('status', 'Lunas');
                })
                ->latest('created_at')
                ->with('pemesanan.penyewa')
                ->first();

            if ($itemTerakhir && $itemTerakhir->pemesanan) {
                $pemesanan = $itemTerakhir->pemesanan;
                $penyewa = $itemTerakhir->pemesanan->penyewa;

                // Hitung durasi sewa
                $checkin = Carbon::parse($itemTerakhir->waktu_checkin);
                $checkout = Carbon::parse($itemTerakhir->waktu_checkout);
                $durasi = $checkin->format('d M') . ' - ' . $checkout->format('d M Y') .
                            ' (' . $checkin->diffInDays($checkout) . ' Malam)';
            }
        }

        return view('pengelola.data-kamar.show', compact('kamar', 'penyewa', 'pemesanan', 'durasi'));
    }

    /**
     * Form edit kamar
     */
    public function edit(Kamar $kamar)
    {
        Gate::authorize('update', $kamar);

        return view('pengelola.data-kamar.edit', compact('kamar'));
    }

    /**
     * Update kamar
     */
    public function update(Request $request, Kamar $kamar)
    {
        Gate::authorize('update', $kamar);

        $validasi = $request->validate([
            'no_kamar'      => ['required', 'max:10'],
            'tipe_kamar'    => ['required', 'in:Ekonomis,Standar'],
            'harga_kamar'   => ['required', 'numeric'],
            'deskripsi'     => ['required'],
            'status'        => ['required', 'in:Tersedia,Dihuni'],
            'gambar'        => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('gambar')) {
            if ($kamar->gambar && Storage::disk('public')->exists($kamar->gambar)) {
                Storage::disk('public')->delete($kamar->gambar);
            }

            $validasi['gambar'] = $request->file('gambar')
                ->store('kamar-images', 'public');
        }

        $kamar->update($validasi);

        Alert::success('Berhasil!', 'Data kamar berhasil diperbarui.');
        return redirect()->route('pengelola.kamar.index');
    }

    /**
     * Hapus kamar
     */
    public function destroy(Kamar $kamar)
    {
        Gate::authorize('delete', $kamar);

        if ($kamar->gambar && Storage::disk('public')->exists($kamar->gambar)) {
            Storage::disk('public')->delete($kamar->gambar);
        }

        $kamar->delete();

        Alert::success('Berhasil!', 'Data kamar berhasil dihapus.');
        return redirect()->route('pengelola.kamar.index');
    }
}
