<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        // Mengambil semua transaksi dengan relasi penyewa dan cabang
        $transaksi = Pemesanan::with(['penyewa', 'cabang'])
            ->orderBy('waktu_pemesanan', 'desc')
            ->get();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function show($id)
    {
        // Mengambil detail transaksi beserta item kamar dan service
        $transaksi = Pemesanan::with([
            'penyewa', 
            'cabang', 
            'items.kamar', 
            'service.service', 
            'pembayaran'
        ])->findOrFail($id);

        return view('admin.transaksi.show', compact('transaksi'));
    }
}