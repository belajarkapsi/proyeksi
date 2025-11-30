<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Schema; 

class KamarController extends Controller
{

    public function index(Request $request)
    {
        $cabang = $request->get('cabang');

        if($cabang) {
            $query = $cabang->kamars();
        }else {
            $query = Kamar::query();
        }

        $rooms = $query ->orderBy('no_kamar', 'asc')
                        ->orderBy('status', 'desc')
                        ->orderBy('tipe_kamar', 'desc')
                        ->get();

        // --- tambahan: kirim daftar layanan ke view hanya jika cabang adalah villa
        if ($cabang && strtolower(trim($cabang->kategori_cabang ?? '')) === 'villa') {
        if (class_exists(\App\Models\Service::class) && Schema::hasTable('services')) {
        $services = \App\Models\Service::all();
        } else {
            $services = collect();
         }
        } else {
            $services = collect();
        }

        return view('kamar.daftar-kamar', compact('rooms', 'cabang', 'services'));
    }

    public function show($lokasi, $kategori, $no_kamar)
    {
        $room = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        $cabang = $room->cabang;

        return view('kamar.detail-kamar', compact('cabang', 'room'));
    }
}

