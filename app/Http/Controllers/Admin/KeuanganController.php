<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class KeuanganController extends Controller
{
    public function index(Request $request)
{
    $bulan  = $request->bulan ?? now()->month;
    $tahun  = $request->tahun ?? now()->year;
    $cabang = $request->cabang;

    $query = Pemesanan::whereMonth('waktu_pemesanan', $bulan)
                      ->whereYear('waktu_pemesanan', $tahun);

    if ($cabang) {
        $query->where('id_cabang', $cabang);
    }

    $totalPendapatan = $query->sum('total_harga');
    $jumlahTransaksi = $query->count();
    $rataRata = $jumlahTransaksi > 0
        ? round($totalPendapatan / $jumlahTransaksi, 2)
        : 0;

    $transaksi = $query->latest()->get();

    $grafik = Pemesanan::select(
            DB::raw('DAY(waktu_pemesanan) as hari'),
            DB::raw('SUM(total_harga) as total')
        )
        ->whereMonth('waktu_pemesanan', $bulan)
        ->whereYear('waktu_pemesanan', $tahun)
        ->groupBy('hari')
        ->get();

    return view('admin.laporan-keuangan.index', compact(
        'totalPendapatan','jumlahTransaksi',
        'rataRata','transaksi','grafik',
        'bulan','tahun','cabang'
    ));
}

public function pdf(Request $request)
{
    $data = Pemesanan::whereMonth('waktu_pemesanan', now()->month)
        ->whereYear('waktu_pemesanan', now()->year)
        ->get();

    return Pdf::loadView('admin.laporan-keuangan.pdf', [
        'judul'   => 'Laporan Keuangan',
        'periode' => 'Periode ' . now()->format('F Y'),
        'data'    => $data
    ])->download('laporan-keuangan.pdf');
}
}
