<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    public function show(Request $request)
    {
        $cabang = $request->get('cabang'); // atau $request->attributes->get('cabang')

        $rooms = $cabang->kamars()->orderBy('status', 'desc')->get();
        $types = $rooms->groupBy('tipe_kamar');

        return view('cabang', compact('cabang', 'types'));
    }

    public function type(Request $request, $lokasi, $kategori, $slug = null)
    {
        $cabang = $request->get('cabang');

        $query = $cabang->kamars();

        // Filter slug
        if($slug) {
            $query->where('slug', $slug);
        }

        $rooms = $query->orderBy('status', 'asc') // Tersedia dulu
                    ->latest() // Baru urutkan tanggal input
                    ->paginate(9);

        return view('kamar.daftar-kamar', compact( 'rooms', 'cabang', 'slug'));
    }
}
