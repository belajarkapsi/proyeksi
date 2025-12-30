<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class HunianController extends Controller
{
    public function index(Request $request)
{
    $bulan   = $request->bulan ?? now()->month;
    $tahun   = $request->tahun ?? now()->year;
    $cabang  = $request->cabang;

    $queryKamar = Kamar::query();

    if ($cabang) {
        $queryKamar->where('id_cabang', $cabang);
    }

    $totalKamar   = $queryKamar->count();
    $kamarDihuni  = (clone $queryKamar)->where('status', 'Dihuni')->count();
    $kamarTersedia = $totalKamar - $kamarDihuni;

    $persentaseHunian = $totalKamar > 0
        ? round(($kamarDihuni / $totalKamar) * 100, 2)
        : 0;

    $hunianPerCabang = Kamar::select(
            'cabang.nama_cabang',
            DB::raw('COUNT(kamar.id_kamar) as total_kamar'),
            DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni")
        )
        ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
        ->groupBy('cabang.nama_cabang')
        ->get();

    return view('admin.laporan-hunian.index', compact(
        'totalKamar','kamarDihuni','kamarTersedia',
        'persentaseHunian','hunianPerCabang',
        'bulan','tahun','cabang'
    ));
}

public function pdf(Request $request)
{
    $data = Kamar::select(
            'cabang.nama_cabang',
            DB::raw('COUNT(kamar.id_kamar) as total_kamar'),
            DB::raw("SUM(CASE WHEN kamar.status = 'Dihuni' THEN 1 ELSE 0 END) as dihuni")
        )
        ->join('cabang','kamar.id_cabang','=','cabang.id_cabang')
        ->groupBy('cabang.nama_cabang')
        ->get();

    return Pdf::loadView('admin.laporan-hunian.pdf', [
        'judul'   => 'Laporan Hunian',
        'periode' => 'Periode ' . now()->format('F Y'),
        'data'    => $data
    ])->download('laporan-hunian.pdf');
}
}
