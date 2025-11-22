<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar; // Pastikan ini sesuai nama Model Anda
use App\Models\Pemesanan; // Model untuk simpan
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Menampilkan Daftar Kamar
    public function index()
    {
        $rooms = Kamar::all(); // Atau logic lain sesuai kebutuhan
        return view('kamar.index', compact('rooms'));
    }

    // === JEMBATAN PENGHUBUNG (LOGIC UTAMA) ===
    public function checkout(Request $request)
    {
        // KONDISI 1: Datang dari Form Multi-Select (Method POST)
        if ($request->isMethod('post') && $request->has('selected_rooms')) {
            $ids = $request->input('selected_rooms');

            // Ambil semua kamar yang dipilih dari Database
            // Sesuaikan 'no_kamar' dengan primary key atau kolom unik di tabel Anda
            $rooms = Kamar::whereIn('no_kamar', $ids)->get();
        }

        // KONDISI 2: Datang dari Klik Cepat Single (Method GET)
        else if ($request->has('kamar')) {
            $noKamar = $request->query('kamar');
            $room = Kamar::where('no_kamar', $noKamar)->first();

            if (!$room) {
                return redirect()->route('kamar.index')->with('error', 'Kamar tidak ditemukan');
            }

            // Bungkus kamar single menjadi Collection agar formatnya sama dengan multi
            $rooms = collect([$room]);
        }

        // Jika diakses tanpa data
        else {
            return redirect()->route('kamar.index');
        }

        // Hitung Total Harga Dasar (Harga per malam total semua kamar)
        // Pastikan kolom harga di database bertipe angka (integer/double)
        $totalBasePrice = $rooms->sum('harga');

        // Lempar data ke View Checkout yang baru
        return view('kamar/booking', compact('rooms', 'totalBasePrice'));
    }

    // Proses Simpan Akhir
    // BookingController.php

public function store(Request $request)
{
    // 1. Validasi
    $validated = $request->validate([
        'nama_lengkap' => 'required|string|max:255',
        'telepon'      => 'required|numeric',
        'email'        => 'required|email',
        'tanggal'      => 'required|date',

        // Validasi Durasi menjadi Array
        'durasi'       => 'required|array',
        'durasi.*'     => 'integer|min:1', // Tiap durasi minimal 1 hari

        'kamar_ids'    => 'required|array',
        'kamar_ids.*'  => 'exists:kamars,id',
    ]);

    $waktuCheckin = Carbon::parse($validated['tanggal']);

    // 2. Looping setiap kamar
    foreach ($validated['kamar_ids'] as $kamarId) {
        $kamar = Kamar::find($kamarId);

        // AMBIL DURASI SPESIFIK UNTUK KAMAR INI
        // $request->durasi adalah array: [id_kamar => lama_hari]
        $lamaSewa = (int) $validated['durasi'][$kamarId];

        // Hitung Total Harga Kamar Ini (Harga x Durasi Spesifik)
        $totalHarga = $kamar->harga * $lamaSewa;

        // Hitung Tanggal Checkout Spesifik
        $waktuCheckout = (clone $waktuCheckin)->addDays($lamaSewa);

        Pemesanan::create([
            'id_penyewa'       => Auth::id() ?? null,
            'id_kamar'         => $kamar->id,
            'jumlah_pemesanan' => 1,
            'harga'            => $totalHarga,
            'waktu_pemesanan'  => now(),
            'waktu_checkin'    => $waktuCheckin,
            'waktu_checkout'   => $waktuCheckout, // Checkout berbeda tiap kamar
            'nama_pemesan'     => $validated['nama_lengkap'],
            'kontak_pemesan'   => $validated['telepon'],
        ]);
    }

    return redirect()->route('kamar.index')->with('success', 'Pemesanan berhasil! Cek detail tiap kamar.');
}

    // Detail Kamar (Opsional)
    public function detail($no_kamar)
    {
        $room = Kamar::where('no_kamar', $no_kamar)->firstOrFail();
        return view('kamar.detail-kamar', compact('room'));
    }
}
