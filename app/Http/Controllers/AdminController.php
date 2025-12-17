<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Kamar;
use App\Models\Cabang;
use App\Models\Service;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $totalBooking  = Pemesanan::count();
        $totalKamar    = Kamar::count();
        $kamarTerisi   = Kamar::where('status', 'Dihuni')->count();
        $kamarTersedia = max($totalKamar - $kamarTerisi, 0);
        $totalCabang  = Cabang::count();
        $totalLayanan = Service::count();
        $totalPenyewa = User::count();

        // ===== AREA CHART =====
        $areaChart = Pemesanan::selectRaw('DATE(waktu_pemesanan) as tanggal, COUNT(*) as total')
            ->whereDate('waktu_pemesanan', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // ===== BAR CHART =====
        $barChart = Pemesanan::selectRaw('MONTH(waktu_pemesanan) as bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        return view('admin.dashboard', compact(
            'totalBooking',
            'totalKamar',
            'kamarTerisi',
            'kamarTersedia',
            'totalCabang',
            'totalLayanan',
            'totalPenyewa',
            'areaChart',
            'barChart'
        ));
    }
}
