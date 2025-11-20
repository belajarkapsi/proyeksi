<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Tampilkan form booking
    public function create(Request $request)
    {
        $kamarId   = $request->query('kamar'); // id_kamar
        $harga     = $request->query('harga'); // harga per hari (angka)
        $namaKamar = $request->query('nama_kamar') ?? 'Kamar';
        $gambar    = $request->query('gambar') ?? null;
        $lantai    = $request->query('lantai') ?? null;
        $cabangId  = $request->query('cabang') ?? null; // optional

        $hargaInt = is_numeric($harga) ? (float) $harga : 0;

        return view('/kamar/booking', compact('kamarId', 'hargaInt', 'namaKamar', 'gambar', 'lantai', 'cabangId'));
    }

    // Simpan booking (simpan ke tabel pemesanan)
    public function store(Request $request)
    {
        // validasi input
        $validated = $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'telepon'          => 'required|string|max:50',
            'email'            => 'required|email',
            'username'         => 'nullable|string|max:100',
            'kota_asal'        => 'nullable|string|max:100',
            'tanggal'          => 'required|date',
            'durasi'           => 'required|integer|min:1',
            'jumlah'           => 'required|integer|min:1',
            'kamar_id'         => 'required|integer',
            'harga_per_hari'   => 'required|numeric',
            'cabang_id'        => 'nullable|integer',
        ]);

        // Ambil id_penyewa jika user sudah login (asumsi ada kolom id_penyewa di users or related)
        $idPenyewa = null;
        if (Auth::check()) {
            $user = Auth::user();

            /**
             * PERHATIAN:
             * - Jika model User Anda punya kolom id_penyewa, gunakan $user->id_penyewa
             * - Jika relasi penyewa ada (mis. $user->penyewa->id_penyewa), sesuaikan di bawah.
             * Contoh umum:
             *   $idPenyewa = $user->id_penyewa ?? null;
             */
            if (isset($user->id_penyewa)) {
                $idPenyewa = $user->id_penyewa;
            } elseif (method_exists($user, 'penyewa') && $user->penyewa) {
                $idPenyewa = $user->penyewa->id_penyewa;
            } else {
                // tidak ditemukan id_penyewa otomatis, tetap null atau Anda bisa buat logika pembuatan penyewa baru
                $idPenyewa = null;
            }
        }

        // Hitung total harga dan waktu pemesanan
        $hargaPerHari = (float) $validated['harga_per_hari'];
        $durasi = (int) $validated['durasi'];
        $jumlah = (int) $validated['jumlah'];

        // Total yang akan disimpan di kolom `harga` sesuai model Anda:
        // (Anda bisa memilih menyimpan per-hari atau total; di contoh ini disimpan total)
        $totalHarga = $hargaPerHari * $durasi * $jumlah;

        // Waktu pemesanan adalah sekarang
        $waktuPemesanan = Carbon::now();

        // waktu_checkin = tanggal yg dipilih (format Y-m-d)
        $waktuCheckin = Carbon::parse($validated['tanggal'])->startOfDay();

        // waktu_checkout = checkin + durasi hari
        // Jika durasi=1 maka checkout = checkin + 1 hari (cek kebutuhan bisnis: apakah checkout = same day + 1)
        $waktuCheckout = (clone $waktuCheckin)->addDays($durasi);

        // Simpan ke tabel pemesanan (sesuaikan nama kolom dengan migration Anda)
        $pemesanan = Pemesanan::create([
            'id_penyewa'       => $idPenyewa,
            'id_cabang'        => $validated['cabang_id'] ?? null,
            'id_kamar'         => $validated['kamar_id'],
            'jumlah_pemesanan' => $jumlah,
            'harga'            => $totalHarga,
            'waktu_pemesanan'  => $waktuPemesanan,
            'waktu_checkin'    => $waktuCheckin,
            'waktu_checkout'   => $waktuCheckout,
        ]);

        // Jika ingin juga menyimpan data penyewa (nama, telepon, email) ke tabel penyewa,
        // lakukan di sini (misalnya Buat Penyewa baru jika id_penyewa null). Saya tidak autosave
        // karena struktur Penyewa Anda tidak saya tahu — tapi bisa ditambahkan.

        // Redirect atau tampilkan success
        return redirect()->route('booking.create', [
            'kamar' => $validated['kamar_id'],
            'harga' => $hargaPerHari,
            'nama_kamar' => $request->nama_kamar ?? null
        ])->with('success', 'Permintaan sewa berhasil disimpan. ID Pemesanan: ' . $pemesanan->getKey());
    }
}
