<?php

namespace App\Http\Middleware;

use App\Models\Cabang;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidasiCabang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $lokasi = $request->route('lokasi');
        $kategori = $request->route('kategori');

        $lokasiQuery = strtolower(str_replace('-', ' ', $lokasi));
        $kategoriQuery = strtolower(str_replace('-', ' ', $kategori));

        $cabang = Cabang::whereRaw('LOWER(lokasi) = ?', [$lokasiQuery])
                        ->whereRaw('LOWER(kategori_cabang) = ?', [$kategoriQuery])
                        ->first();

        if (!$cabang) {
            return abort(404, 'Cabang tidak ditemukan');
        }

        // simpan model cabang ke request agar controller tidak perlu query ulang
        $request->attributes->set('cabang', $cabang);

        return $next($request);
    }
}
