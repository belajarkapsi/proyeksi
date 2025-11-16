<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function show(string $lokasi, string $kategori)
    {
        $lokasiQuery = str_replace('-', ' ', $lokasi);
        $kategoriQuery = str_replace('-', ' ', $kategori);
        
        $cabang = Cabang::whereRaw('LOWER(lokasi) = ?', [strtolower($lokasiQuery)])
                    ->whereRaw('LOWER(kategori_cabang) = ?', [strtolower($kategoriQuery)])
                    ->firstOrFail();
                    
        return view('cabang', compact('cabang'));
    }
}
