<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\PemesananService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\select;

class LaporanController extends Controller
{
    private function getDataLaporan($request)
    {
        $pengelola = Auth::user();
        $cabang = $pengelola->cabang;

        if(!$cabang) {
            abort(403, 'Anda tidak memiliki akses untuk cabang ini!');
        }

        $isVilla = $cabang->kategori_cabang === 'villa';

        // Filter tanggal
        $tgglMulai = $request->input('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
        $tgglSelesai = $request->input('tanggal_selesai', Carbon::now()->toDateString());

        // Cek pemesanan lunas/selesai
        $pemesananLunas = Pemesanan::where('id_cabang', $cabang->id_cabang)
            ->whereIn('status', ['Lunas', 'Selesai'])
            ->whereBetween(DB::raw('DATE(waktu_pemesanan)'), [$tgglMulai, $tgglSelesai]);

        // Laporan pendapatan
        $totalPendapatan = $pemesananLunas->sum('total_harga');
        $transaksiList   = (clone $pemesananLunas)->with('penyewa')->latest()->limit(10)->get();

        // Grafik Trend Pendapatan Harian
        $trendData = (clone $pemesananLunas)
            ->select(DB::raw('DATE(waktu_pemesanan) as date'), DB::raw('SUM(total_harga) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartTrendLabels = $trendData->pluck('date');
        $chartTrendValues = $trendData->pluck('total');

        // Breakdown Pendapatan (villa)
        $pendapatanService = 0;
        $pendapatanKamar   = $totalPendapatan;

        $pemesananIds = (clone $pemesananLunas)->pluck('id_pemesanan');

        if($isVilla) {
            $pendapatanService = PemesananService::whereIn('id_pemesanan', $pemesananIds)->sum('subtotal');
            $pendapatanKamar   = $totalPendapatan - $pendapatanService;
        }

        // Laporan operasinal (kamar)
        // Kamar Terlaris
        $topKamar = PemesananItem::whereIn('id_pemesanan', $pemesananIds)
            ->join('kamar', 'pemesanan_item.id_kamar', '=', 'kamar.id_kamar')
            ->select('kamar.no_kamar', 'kamar.tipe_kamar', DB::raw('COUNT(*) as total_book'), DB::raw('SUM(pemesanan_item.harga) as omzet'))
            ->groupBy('kamar.id_kamar', 'kamar.no_kamar', 'kamar.tipe_kamar')
            ->orderByDesc('total_book')->limit(5)->get();

        // Hitung Okupansi
        $totalKamar = Kamar::where('id_cabang', $cabang->id_cabang)->count();
        $totalTransaksiKamar = PemesananItem::whereIn('id_pemesanan', $pemesananIds)->count();
        $okupansiRate = $totalKamar > 0 ? ($totalTransaksiKamar / ($totalKamar * 30)) * 100 : 0;

        // Service Analitis untuk villa
        $topServices = collect();
        if($isVilla) {
            $topServices = PemesananService::with('service')
                ->whereIn('id_pemesanan', $pemesananIds)
                ->select('id_service', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(subtotal) as total_omzet'))
                ->groupBy('id_service')
                ->orderByDesc('total_qty')->limit(5)->get();
        }

        // Pie chart
        $statusStats = Pemesanan::where('id_cabang', $cabang->id_cabang)
            ->whereBetween(DB::raw('DATE(waktu_pemesanan)'), [$tgglMulai, $tgglSelesai])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $chartStatusLabels = $statusStats->keys();
        $chartStatusValues = $statusStats->values();

        // Alasan Pembatalan
        $alasanBatal = Pemesanan::where('id_cabang', $cabang->id_cabang)
            ->where('status', 'Dibatalkan')
            ->whereBetween(DB::raw('DATE(waktu_pemesanan)'), [$tgglMulai, $tgglSelesai])
            ->whereNotNull('alasan_batal')
            ->select('alasan_batal', DB::raw('count(*) as total'))
            ->groupBy('alasan_batal')
            ->orderByDesc('total')->limit(5)->get();

        return compact(
            'cabang', 'isVilla', 'tgglMulai', 'tgglSelesai',
            'totalPendapatan', 'transaksiList', 'chartTrendLabels', 'chartTrendValues',
            'pendapatanKamar', 'pendapatanService', 'topKamar', 'okupansiRate',
            'topServices', 'chartStatusLabels', 'chartStatusValues', 'alasanBatal'
        );
    }

    public function index(Request $request)
    {
        $data = $this->getDataLaporan($request);

        // Logika AJAX
        if ($request->ajax()) {
            return response()->json([
                // Data Angka & Text
                'totalPendapatan'   => number_format($data['totalPendapatan'], 0, ',', '.'),
                'pendapatanKamar'   => number_format($data['pendapatanKamar'], 0, ',', '.'),
                'pendapatanService' => number_format($data['pendapatanService'], 0, ',', '.'),
                'periodeText'       => Carbon::parse($data['tgglMulai'])->format('d M') . ' - ' . Carbon::parse($data['tgglSelesai'])->format('d M Y'),

                // Logic Width Bar
                'widthKamar'        => $data['totalPendapatan'] > 0 ? ($data['pendapatanKamar'] / $data['totalPendapatan']) * 100 : 0,
                'widthService'      => $data['totalPendapatan'] > 0 ? ($data['pendapatanService'] / $data['totalPendapatan']) * 100 : 0,

                'trendLabels'       => $data['chartTrendLabels'],
                'trendValues'       => $data['chartTrendValues'],
                'statusLabels'      => $data['chartStatusLabels'],
                'statusValues'      => $data['chartStatusValues'],

                'htmlTopRooms'      => view('pengelola.laporan.partials.tabel-kamar', ['topKamar' => $data['topKamar']])->render(),
                'htmlTopServices'   => $data['isVilla'] ? view('pengelola.laporan.partials.tabel-service', ['topServices' => $data['topServices']])->render() : '',
                'htmlCancelReasons' => view('pengelola.laporan.partials.alasan-batal', ['alasanBatal' => $data['alasanBatal']])->render(),
            ]);
        }

        return view('pengelola.laporan.index', $data);
    }

    public function cetak(Request $request)
    {
        $data = $this->getDataLaporan($request);

        $pdf = Pdf::loadView('pengelola.laporan.pdf', $data);
        $pdf->setPaper('A4', 'potrait');

        $namaFile = 'Laporan_SIPESTAR_' . $data['tgglMulai'] . '_sd_' . $data['tgglSelesai'] . '.pdf';
        return $pdf->stream($namaFile);
    }
}
