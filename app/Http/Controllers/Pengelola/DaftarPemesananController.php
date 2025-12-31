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
    public function index(Request $request)
    {
        $pengelola = Auth::user();
        if (!$pengelola->cabang) {
            abort(403, 'Akun Anda tidak terhubung dengan cabang manapun.');
        }

        $idCabang = $pengelola->cabang->id_cabang;
        $query = Pemesanan::query()
            ->where('id_cabang', $idCabang)
            ->with(['penyewa', 'items.kamar', 'service.service', 'cabang']);

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

    public function destroy($id)
    {
        $pengelola = Auth::user();
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->firstOrFail();
        $pemesanan->delete();
        return redirect()->back()->with('success', 'Pemesanan berhasil dihapus.');
    }

    public function verifikasi($id)
    {
        $pengelola = Auth::user();

        DB::transaction(function () use ($id, $pengelola) {
            $pemesanan = Pemesanan::where('id_pemesanan', $id)
                ->where('id_cabang', $pengelola->cabang->id_cabang)
                ->with('items')
                ->firstOrFail();

            // 1. Update Status Pemesanan ini menjadi Lunas
            $pemesanan->update(['status' => 'Lunas']);

            // 2. Ambil semua ID Kamar dalam pemesanan ini
            $kamarIds = $pemesanan->items->pluck('id_kamar')->toArray();

            // 3. Ubah status kamar menjadi Dihuni
            Kamar::whereIn('id_kamar', $kamarIds)->update(['status' => 'Dihuni']);

            // 4. LOGIKA AUTO-CANCEL:
            // Batalkan semua pemesanan lain yang mengandung kamar yang sama dan masih berstatus 'Belum Dibayar'
            Pemesanan::where('id_pemesanan', '!=', $id)
                ->where('status', 'Belum Dibayar')
                ->whereHas('items', function($query) use ($kamarIds) {
                    $query->whereIn('id_kamar', $kamarIds);
                })
                ->update([
                    'status' => 'Dibatalkan',
                    'cancelled_at' => now(),
                    'alasan_batal' => 'Kamar telah diverifikasi untuk pemesan lain.'
                ]);
        });

        Alert::success('Berhasil', 'Pemesanan berhasil diverifikasi dan pesanan bentrok lainnya otomatis dibatalkan.');
        return redirect()->back();
    }

    public function batalkan(Request $request, $id)
    {
        $request->validate(['alasan_batal' => 'required|string|max:500']);
        $pengelola = Auth::user();

        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->firstOrFail();

        $pemesanan->update([
            'status' => 'Dibatalkan',
            'cancelled_at' => now(),
            'alasan_batal' => $request->alasan_batal
        ]);

        Alert::error('Dibatalkan', 'Pemesanan berhasil dibatalkan.');
        return redirect()->back();
    }
}