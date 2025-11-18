<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function show(Request $request)
    {
        $cabang = $request->get('cabang'); // atau $request->attributes->get('cabang')

        $allRooms = $cabang->kamars()->get();
        $types = $allRooms->groupBy('tipe_kamar');
        
        $kamars = $cabang->kamars()->latest()->paginate(12);
                    
        return view('cabang', compact('cabang', 'types', 'kamars'));
    }
}
