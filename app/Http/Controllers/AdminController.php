<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Kamar;
use App\Models\Cabang;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Statistik Utama (7 Kartu)
        $totalBooking   = Pemesanan::count();
        $totalKamar     = Kamar::count();
        $kamarDihuni    = Kamar::where('status', 'Dihuni')->count();
        $kamarTersedia  = $totalKamar - $kamarDihuni;
        $totalCabang    = Cabang::count();
        $totalLayanan   = Service::count();
        $totalPenyewa   = User::where('role', 'penyewa')->count();
        $persentaseHunian = $totalKamar > 0 ? round(($kamarDihuni / $totalKamar) * 100, 2) : 0;

        // 2. Logika Cabang (Okupansi per Cabang - LOGIKA TETAP)
        $hunianPerCabang = Kamar::select(
                'cabang.nama_cabang',
                DB::raw('COUNT(kamar.id_kamar) as total_kamar'),
                DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni")
            )
            ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
            ->groupBy('cabang.nama_cabang')
            ->get();

        // 3. Logika Layanan (Sesuai Model: name & pemesananService)
        $dataLayanan = Service::withCount('pemesananService')->get();

        // 4. Area Chart (7 Hari Terakhir)
        $areaChart = Pemesanan::selectRaw('DATE(waktu_pemesanan) as tanggal, COUNT(*) as total')
            ->whereDate('waktu_pemesanan', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // 5. Tabel Penyewa (3 Terbaru)
        $penyewaTerbaru = User::where('role', 'penyewa')->orderBy('created_at', 'desc')->take(3)->get();

        return view('admin.dashboard', compact(
            'totalBooking', 'totalKamar', 'kamarDihuni', 'kamarTersedia', 
            'totalCabang', 'totalLayanan', 'totalPenyewa', 'persentaseHunian',
            'hunianPerCabang', 'dataLayanan', 'areaChart', 'penyewaTerbaru'
        ));
    }

    public function getDashboardData()
    {
        $totalKamar  = Kamar::count();
        $kamarDihuni = Kamar::where('status', 'Dihuni')->count();

        // Re-fetch data untuk sinkronisasi AJAX
        $hunianPerCabang = Kamar::select('cabang.nama_cabang', DB::raw("SUM(CASE WHEN kamar.status='Dihuni' THEN 1 ELSE 0 END) as dihuni"))
            ->join('cabang', 'kamar.id_cabang', '=', 'cabang.id_cabang')
            ->groupBy('cabang.nama_cabang')->get();

        $layanan = Service::withCount('pemesananService')->get();

        return response()->json([
            'stats' => [
                'totalBooking'  => Pemesanan::count(),
                'totalKamar'    => $totalKamar,
                'kamarDihuni'   => $kamarDihuni,
                'kamarTersedia' => $totalKamar - $kamarDihuni,
                'totalCabang'   => Cabang::count(),
                'totalLayanan'  => Service::count(),
                'totalPenyewa'  => User::where('role', 'penyewa')->count(),
            ],
            'area' => [
                'labels' => Pemesanan::selectRaw('DATE(waktu_pemesanan) as t')->groupBy('t')->orderBy('t')->take(7)->pluck('t'),
                'values' => Pemesanan::selectRaw('COUNT(*) as c')->groupBy(DB::raw('DATE(waktu_pemesanan)'))->orderBy(DB::raw('DATE(waktu_pemesanan)'))->take(7)->pluck('c'),
            ],
            'cabang' => [
                'labels' => $hunianPerCabang->pluck('nama_cabang'),
                'values' => $hunianPerCabang->pluck('dihuni'),
            ],
            'layanan' => [
                'labels' => $layanan->pluck('name'),
                'values' => $layanan->pluck('pemesanan_service_count'),
            ],
            'penyewaTerbaru' => User::where('role', 'penyewa')->orderBy('created_at', 'desc')->take(3)->get(['nama_lengkap', 'username'])
        ]);
    }
}