<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\Cabang;
use App\Models\PemesananItem;
use App\Models\PemesananService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan      = $request->bulan ?? now()->month;
        $tahun      = $request->tahun ?? now()->year;
        $cabang_id  = $request->cabang_id;
        $cabangs    = Cabang::all();

        // Query Keuangan Dasar
        $queryKeuangan = Pemesanan::whereMonth('waktu_pemesanan', $bulan)
                                ->whereYear('waktu_pemesanan', $tahun);
        if ($cabang_id) { $queryKeuangan->where('id_cabang', $cabang_id); }
        
        $totalPendapatan = $queryKeuangan->sum('total_harga');
        $jumlahTransaksi = $queryKeuangan->count();
        $rataRata        = $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;
        $transaksi       = $queryKeuangan->with(['penyewa', 'cabang'])->latest()->limit(10)->get();

        // Analisis Hunian (Real-time)
        $queryKamar = Kamar::query();
        if ($cabang_id) { $queryKamar->where('id_cabang', $cabang_id); }
        $totalKamar       = $queryKamar->count();
        $kamarDihuni      = (clone $queryKamar)->where('status', 'Dihuni')->count();
        $kamarTersedia    = $totalKamar - $kamarDihuni;
        $persentaseHunian = $totalKamar > 0 ? round(($kamarDihuni / $totalKamar) * 100, 2) : 0;

        $hunianPerCabang = Kamar::select('cabang.id_cabang', 'cabang.nama_cabang',
                DB::raw('COUNT(kamar.id_kamar) as total_kamar'),
                DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni")
            )
            ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
            ->groupBy('cabang.id_cabang', 'cabang.nama_cabang')->get();

        return view('admin.laporan.index', compact(
            'cabangs', 'totalPendapatan', 'jumlahTransaksi', 'rataRata', 'transaksi', 
            'totalKamar', 'kamarDihuni', 'kamarTersedia', 'persentaseHunian', 'hunianPerCabang',
            'bulan', 'tahun'
        ));
    }

    public function downloadPdf(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $cabang_id = $request->cabang_id;
        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));
        
        $cabang = $cabang_id ? Cabang::find($cabang_id) : null;
        $namaCabangHeader = $cabang ? $cabang->nama_cabang : 'Semua Cabang';

        // Query Transaksi Utama
        $queryPemesanan = Pemesanan::with(['penyewa', 'cabang'])
            ->whereMonth('waktu_pemesanan', $bulan)
            ->whereYear('waktu_pemesanan', $tahun);
        if ($cabang_id) { $queryPemesanan->where('id_cabang', $cabang_id); }
        $transaksi = $queryPemesanan->latest()->get();

        // Query Detail Item
        $detailItems = PemesananItem::with(['kamar', 'pemesanan.penyewa'])
            ->whereHas('pemesanan', function($q) use ($bulan, $tahun, $cabang_id) {
                $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
                if ($cabang_id) $q->where('id_cabang', $cabang_id);
            })->get();

        // Query Detail Service
        $detailServices = PemesananService::with(['service', 'pemesanan.penyewa'])
            ->whereHas('pemesanan', function($q) use ($bulan, $tahun, $cabang_id) {
                $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
                if ($cabang_id) $q->where('id_cabang', $cabang_id);
            })->get();

        // Data Okupansi
        $hunianPerCabang = Kamar::select('cabang.nama_cabang', 
                DB::raw('COUNT(kamar.id_kamar) as total_kamar'), 
                DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni"))
            ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
            ->when($cabang_id, fn($q) => $q->where('kamar.id_cabang', $cabang_id))
            ->groupBy('cabang.nama_cabang')->get();

        $data = [
            'periode'         => "$namaBulan $tahun",
            'namaCabang'      => $namaCabangHeader,
            'totalPendapatan' => $transaksi->sum('total_harga'),
            'jumlahTransaksi' => $transaksi->count(),
            'hunianPerCabang' => $hunianPerCabang,
            'transaksi'       => $transaksi,
            'detailItems'     => $detailItems,
            'detailServices'  => $detailServices
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', $data);
        return $pdf->setPaper('a4', 'portrait')->stream("Laporan_{$namaCabangHeader}_{$namaBulan}.pdf");
    }

    public function getChartData(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $cabang_id = $request->cabang_id;

        // Data Chart
        $queryGrafik = Pemesanan::select(DB::raw('DAY(waktu_pemesanan) as hari'), DB::raw('SUM(total_harga) as total'))
            ->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
        if ($cabang_id) $queryGrafik->where('id_cabang', $cabang_id);
        $grafik = $queryGrafik->groupBy('hari')->orderBy('hari')->get();

        // Cek Nama Cabang untuk Logika Villa
        $cabang = Cabang::find($cabang_id);
        $isVilla = ($cabang && str_contains(strtolower($cabang->nama_cabang), 'villa')) || !$cabang_id;

        // Data Layanan Real-time
        $layanan = PemesananService::with(['service', 'pemesanan.penyewa'])
            ->whereHas('pemesanan', function($q) use ($bulan, $tahun, $cabang_id) {
                $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
                if ($cabang_id) $q->where('id_cabang', $cabang_id);
            })->latest()->limit(5)->get()->map(function($item){
                return [
                    'penyewa' => $item->pemesanan->penyewa->nama_lengkap ?? 'Guest',
                    'service' => $item->service->name ?? 'Service', // Menggunakan 'name' sesuai Model Service
                    'total'   => number_format($item->subtotal, 0, ',', '.'),
                    'waktu'   => $item->created_at->format('d/m H:i')
                ];
            });

        return response()->json([
            'labels' => $grafik->pluck('hari'),
            'values' => $grafik->pluck('total'),
            'isVilla' => $isVilla,
            'layanan' => $layanan,
            'stats' => [
                'totalPendapatan' => (int)Pemesanan::whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun)->when($cabang_id, fn($q) => $q->where('id_cabang', $cabang_id))->sum('total_harga'),
                'jumlahTransaksi' => Pemesanan::whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun)->when($cabang_id, fn($q) => $q->where('id_cabang', $cabang_id))->count(),
                'kamarDihuni' => Kamar::when($cabang_id, fn($q) => $q->where('id_cabang', $cabang_id))->where('status', 'Dihuni')->count(),
                'kamarTersedia' => Kamar::when($cabang_id, fn($q) => $q->where('id_cabang', $cabang_id))->where('status', '!=', 'Dihuni')->count(),
            ]
        ]);
    }
}