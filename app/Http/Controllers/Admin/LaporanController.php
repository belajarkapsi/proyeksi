<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\Cabang;
use App\Models\PemesananItem;
use App\Models\PemesananService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan Dashboard Laporan di Browser
     */
    public function index(Request $request)
    {
        // 1. Inisialisasi Filter Bulan & Tahun
        $bulan  = $request->bulan ?? now()->month;
        $tahun  = $request->tahun ?? now()->year;

        // 2. Query Dasar Keuangan (Filter berdasarkan Waktu Pemesanan)
        $queryKeuangan = Pemesanan::whereMonth('waktu_pemesanan', $bulan)
                                ->whereYear('waktu_pemesanan', $tahun);
        
        $totalPendapatan = $queryKeuangan->sum('total_harga');
        $jumlahTransaksi = $queryKeuangan->count();
        $rataRata        = $jumlahTransaksi > 0 ? $totalPendapatan / $jumlahTransaksi : 0;
        $transaksi       = $queryKeuangan->with(['penyewa', 'cabang'])->latest()->limit(10)->get();

        // 3. Data Grafik Pendapatan Harian
        $grafik = Pemesanan::select(
                DB::raw('DAY(waktu_pemesanan) as hari'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereMonth('waktu_pemesanan', $bulan)
            ->whereYear('waktu_pemesanan', $tahun)
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        // 4. Analisis Hunian Kamar (Real-time)
        $totalKamar       = Kamar::count();
        $kamarDihuni      = Kamar::where('status', 'Dihuni')->count();
        $kamarTersedia    = $totalKamar - $kamarDihuni;
        $persentaseHunian = $totalKamar > 0 ? round(($kamarDihuni / $totalKamar) * 100, 2) : 0;

        $hunianPerCabang = Kamar::select(
                'cabang.nama_cabang',
                DB::raw('COUNT(kamar.id_kamar) as total_kamar'),
                DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni")
            )
            ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
            ->groupBy('cabang.nama_cabang')
            ->get();

        // 5. Analisis Layanan Tambahan (Service) & Item Kamar
        $totalPendapatanService = PemesananService::whereHas('pemesanan', function($q) use ($bulan, $tahun) {
            $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
        })->sum('subtotal');

        $totalItemTerjual = PemesananItem::whereHas('pemesanan', function($q) use ($bulan, $tahun) {
            $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
        })->count();

        return view('admin.laporan.index', compact(
            'totalPendapatan', 'jumlahTransaksi', 'rataRata', 'transaksi', 'grafik',
            'totalKamar', 'kamarDihuni', 'kamarTersedia', 'persentaseHunian', 'hunianPerCabang',
            'totalPendapatanService', 'totalItemTerjual', 'bulan', 'tahun'
        ));
    }

    /**
     * Preview / Stream PDF Laporan Terpadu
     */
    public function downloadPdf(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

        // Data Transaksi Utama
        $transaksi = Pemesanan::with(['penyewa', 'cabang'])
            ->whereMonth('waktu_pemesanan', $bulan)
            ->whereYear('waktu_pemesanan', $tahun)
            ->latest()->get();

        // Data Detail Item (Kamar)
        $detailItems = PemesananItem::with(['kamar', 'pemesanan.penyewa'])
            ->whereHas('pemesanan', function($q) use ($bulan, $tahun) {
                $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
            })->get();

        // Data Detail Service (Laundry, Catering, dll)
        $detailServices = PemesananService::with(['service', 'pemesanan.penyewa'])
            ->whereHas('pemesanan', function($q) use ($bulan, $tahun) {
                $q->whereMonth('waktu_pemesanan', $bulan)->whereYear('waktu_pemesanan', $tahun);
            })->get();

        $data = [
            'periode'         => "$namaBulan $tahun",
            'totalPendapatan' => $transaksi->sum('total_harga'),
            'jumlahTransaksi' => $transaksi->count(),
            'hunianPerCabang' => Kamar::select('cabang.nama_cabang', DB::raw('COUNT(kamar.id_kamar) as total_kamar'), DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni"))
                                ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
                                ->groupBy('cabang.nama_cabang')->get(),
            'transaksi'       => $transaksi,
            'detailItems'     => $detailItems,
            'detailServices'  => $detailServices
        ];

        $pdf = Pdf::loadView('admin.laporan.pdf', $data);
        return $pdf->setPaper('a4', 'portrait')->stream("Laporan_Terpadu_{$namaBulan}.pdf");
    }

    public function getChartData(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $grafik = Pemesanan::select(
                DB::raw('DAY(waktu_pemesanan) as hari'),
                DB::raw('SUM(total_harga) as total')
            )
            ->whereMonth('waktu_pemesanan', $bulan)
            ->whereYear('waktu_pemesanan', $tahun)
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        return response()->json([
            'labels' => $grafik->pluck('hari'),
            'values' => $grafik->pluck('total'),
            'stats' => [
                'kamarDihuni' => Kamar::where('status', 'Dihuni')->count(),
                'kamarTersedia' => Kamar::where('status', '!=', 'Dihuni')->count(),
            ]
        ]);
    }
}