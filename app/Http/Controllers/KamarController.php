<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class KamarController extends Controller
{
     public function show(Kamar $room)
    {

        // jika butuh relasi, eager load mis. images atau fasilitas:
        // $room->load('images','facilities');

        return view('kamar.detail-kamar', compact('room'));
    }
}
