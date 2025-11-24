<?php

namespace App\Http\Controllers;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemesanan;
use RealRashid\SweetAlert\Facades\Alert; // Jangan lupa ini
use Symfony\Component\HttpFoundation\Response;

class PastikanKepemilikanUser extends Controller
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Ambil parameter 'id_pemesanan' dari URL Route
        $idPemesanan = $request->route('id_pemesanan');

        if ($idPemesanan) {
            // 2. Cari data pemesanan ringkas saja (cukup id_penyewa)
            $pemesanan = Pemesanan::select('id_pemesanan', 'id_penyewa')
                            ->where('id_pemesanan', $idPemesanan)
                            ->first();

            // 3. Jika pesanan ada TAPI id_penyewa-nya beda dengan yang login...
            if ($pemesanan && $pemesanan->id_penyewa !== Auth::user()->id_penyewa) {

                // TENDANG KELUAR!
                // Alert::error('Akses Ditolak!', 'Anda tidak memiliki akses ke data pesanan ini.');
                return redirect()->route('dashboard')
                        ->with('error', 'Anda tidak memiliki akses ke data ini');
            }
        }

        return $next($request);
    }
}
