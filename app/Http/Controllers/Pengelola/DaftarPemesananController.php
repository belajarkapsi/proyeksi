<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DaftarPemesananController extends Controller
{
    /**
     * Menampilkan daftar pemesanan berdasarkan cabang pengelola.
     */
    public function index(Request $request)
    {
        $pengelola = Auth::user();

        if (!$pengelola->cabang) {
            abort(403, 'Akun Anda tidak terhubung dengan cabang manapun.');
        }

        $idCabang = $pengelola->cabang->id_cabang;

        $query = Pemesanan::query()
            ->where('id_cabang', $idCabang)
            ->with(['penyewa', 'items.kamar', 'service.service', 'cabang']); // Eager loading untuk performa view

        // Filter Pencarian (Search by ID atau Username Penyewa)
        if ($request->has('q') && $request->q != '') {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('id_pemesanan', 'like', "%{$keyword}%")
                    ->orWhereHas('penyewa', function($subQ) use ($keyword) {
                        $subQ->where('username', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pemesanan = $query->latest()->paginate(10);

        // Kirim data ke View
        return view('pengelola.daftar-pemesanan.index', [
            'pemesanan' => $pemesanan,
            'q' => $request->q,
            'status' => $request->status
        ]);
    }

    public function create()
    {
        return view('pengelola.daftar-pemesanan.create');
    }

    public function show($id)
    {
        $pengelola = Auth::user();
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->with(['items.kamar', 'service.service', 'penyewa'])
            ->firstOrFail();

        return view('pengelola.daftar-pemesanan.show', compact('pemesanan'));
    }

    /**
     * Hapus Pemesanan.
     */
    public function destroy($id)
    {
        $pengelola = Auth::user();

        // Cari pemesanan & pastikan milik cabang pengelola ini
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->firstOrFail();

        // Hapus (Jika ada relasi items/service, pastikan di Model sudah set cascade on delete atau handle manual disini)
        $pemesanan->delete();

        return redirect()->back()->with('success', 'Pemesanan berhasil dihapus.');
    }

    public function verifikasi($id)
    {
        $pengelola = Auth::user();

        DB::transaction(function () use ($id, $pengelola) {
            // Cari pemesanan & pastikan milik cabang pengelola ini (Security Check)
            $pemesanan = Pemesanan::where('id_pemesanan', $id)
                ->where('id_cabang', $pengelola->cabang->id_cabang)
                ->with('items')
                ->firstOrFail();

            // Update Status
            $pemesanan->update(['status' => 'Lunas']);

            // Ubah status kamar menjadi dihuni
            foreach ($pemesanan->items as $item) {
                Kamar::where('id_kamar', $item->id_kamar)
                ->update(['status' => 'Dihuni']);
            }
        });

        Alert::success('Berhasil', 'Pemesanan berhasil diverifikasi');
        return redirect()->back();
    }

    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|max:500', // Validasi alasan wajib diisi
        ]);

        $pengelola = Auth::user();

        // Cari pemesanan sesuai cabang pengelola
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->firstOrFail();

        // Update Data
        $pemesanan->update([
            'status' => 'Dibatalkan',
            'cancelled_at' => now(),
            'alasan_batal' => $request->alasan_batal
        ]);

        Alert::error('Dibatalkan', 'Pemesanan dibatalkan dengan alasan tertentu.');
        return redirect()->back()->with('success', 'Pemesanan berhasil dibatalkan.');
    }

}
