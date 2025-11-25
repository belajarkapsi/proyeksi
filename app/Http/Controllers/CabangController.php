<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CabangController extends Controller
{
    public function show(Request $request)
    {
        $cabang = $request->get('cabang'); // atau $request->attributes->get('cabang')

        return view('cabang', compact('cabang'));
    }

}
