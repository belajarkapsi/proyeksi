<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pemesanan;
use App\Models\Kamar;
use App\Models\User;
// use Illuminate\Http\Request;

class PengelolaController extends Controller
{
    public function index()
    {
        return view('pengelola.dashboard');
    }
}
