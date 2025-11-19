<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    public function show(Request $request)
    {
        $cabang = $request->get('cabang'); // atau $request->attributes->get('cabang')

        $rooms = $cabang->kamars()->get();
        $types = $rooms->groupBy('tipe_kamar');
                    
        return view('cabang', compact('cabang', 'types'));
    }

    public function type(Request $request)
    {
        $cabang = $request->get('cabang');
        
        $rooms = $cabang->kamars()->latest()->paginate(12);

        return view('tipe-kamar', compact( 'rooms'));
    }
}
