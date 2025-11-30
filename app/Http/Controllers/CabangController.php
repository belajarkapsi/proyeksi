<?php

namespace App\Http\Controllers;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    public function show(Request $request)
    {
        $cabang = $request->get('cabang'); // atau $request->attributes->get('cabang')

        return view('cabang', compact('cabang'));
    }

    public function detailVilla($lokasi, $kategori)
    {
        // Convert slug (malang-kota) → malang kota
        $lokasivilla = strtolower($lokasi);
        $kategorivilla = strtolower($kategori);

        // Cari cabang berdasarkan lokasi & kategori
        $cabang = Cabang::whereRaw("LOWER(REPLACE(lokasi, 'villa', 'detail-')) = ?", [$lokasivilla])
        ->whereRaw("LOWER(REPLACE(kategori_cabang, ' ', '-')) = ?", [$kategorivilla])
        ->first();

        if (!$cabang) {
        abort(404, "Cabang Villa tidak ditemukan");
        }

        // Tampilkan halaman detail villa (tanpa kamar)
        return view('villa.detail-villa', [
        'cabang' => $cabang
        ]);
    }


}
