<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

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

        return view('kamar.daftar-kamar', compact('rooms', 'cabang'));
    }

    public function show($lokasi, $kategori, $no_kamar)
    {
        $room = Kamar::where('no_kamar', $no_kamar)->firstOrFail();

        $cabang = $room->cabang;

        return view('kamar.detail-kamar', compact('cabang', 'room'));
    }
}
