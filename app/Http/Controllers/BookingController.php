<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{
    // Jembatan ke halaman checkout (form booking)
    public function checkout(Request $request)
    {
        // Dari form multi-select (POST)
        if ($request->isMethod('post') && $request->has('selected_rooms')) {
            $nos = $request->input('selected_rooms'); // diasumsikan berisi no_kamar
            $rooms = Kamar::whereIn('no_kamar', $nos)->get();
        }
        // Dari klik cepat single (GET ?kamar=xxx)
        else if ($request->has('kamar')) {
            $noKamar = $request->query('kamar');
            $room = Kamar::where('no_kamar', $noKamar)->first();

            if (!$room) {
                Alert::error('Gagal', 'Kamar tidak ditemukan!');
                return redirect()->route('dashboard');
            }
            $rooms = collect([$room]);
        }
        // Akses tanpa data
        else {
            Alert::error('Gagal', 'Silakan pilih kamar terlebih dahulu sebelum memesan.');
            return redirect()->route('dashboard');
        }

        $firstRoom = $rooms->first();

        // Eager load cabang biar efisien
        if ($firstRoom && !$firstRoom->relationLoaded('cabang')) {
            $firstRoom->load('cabang');
        }

        $cabang = $firstRoom ? $firstRoom->cabang : null;

        // Hitung total harga dasar (per malam) untuk informasi saja
        $totalBasePrice = $rooms->sum('harga_kamar');

        return view('kamar.booking', compact('rooms', 'totalBasePrice', 'cabang'));
    }

    // Simpan pemesanan (header + detail) + lock + cek overlap
    public function store(Request $request)
    {
        // Pastikan user login (karena tabel pemesanan butuh id_penyewa)
        if (!Auth::check()) {
            Alert::error('Eitss', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');

            return redirect()->route('login');
        }

        // 1. Validasi input
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'telepon'      => 'required|numeric',
            'email'        => 'required|email',
            'tanggal'      => 'required|date',

            'kamar_ids'    => 'required|array|min:1',
            'kamar_ids.*'  => 'exists:kamar,id_kamar',

            'durasi'       => 'required|array',
            'durasi.*'     => 'integer|min:1',
        ]);

        // 2. Ambil data kamar yang dipesan
        $kamarIds = $validated['kamar_ids'];
        $kamars   = Kamar::whereIn('id_kamar', $kamarIds)->get();

        if ($kamars->count() !== count($kamarIds)) {
            return back()->withErrors(['booking' => 'Sebagian kamar tidak ditemukan.']);
        }

        $tanggalCheckin = Carbon::parse($validated['tanggal'])->startOfDay();
        $idPenyewa      = Auth::user()->id_penyewa ?? Auth::id(); // sesuaikan dengan modelmu
        $idCabang       = $kamars->first()->id_cabang ?? null;

        try {
            $pemesanan = DB::transaction(function () use (
                $kamarIds,
                $kamars,
                $validated,
                $tanggalCheckin,
                $idPenyewa,
                $idCabang
            ) {
                // 2.a. Lock semua baris kamar yang akan dipesan
                DB::table('kamar')
                    ->whereIn('id_kamar', $kamarIds)
                    ->lockForUpdate()
                    ->get();

                $totalHargaHeader = 0;
                $itemsData = [];

                foreach ($kamarIds as $idKamar) {
                    $kamar = $kamars->firstWhere('id_kamar', $idKamar);

                    if (!$kamar) {
                        throw new \Exception("Kamar dengan ID $idKamar tidak ditemukan.");
                    }

                    // Durasi untuk kamar ini (dalam hari)
                    if (!isset($validated['durasi'][$idKamar])) {
                        throw new \Exception("Durasi sewa untuk kamar {$kamar->no_kamar} belum diisi.");
                    }

                    $lamaSewa = (int) $validated['durasi'][$idKamar];
                    $checkin  = clone $tanggalCheckin;
                    $checkout = (clone $tanggalCheckin)->addDays($lamaSewa);

                    // 2.b. Cek overlapped date di pemesanan_item
                    $adaOverlap = PemesananItem::where('id_kamar', $idKamar)
                        ->where(function ($q) use ($checkin, $checkout) {
                            // NOT (checkout_lama <= checkin_baru OR checkin_lama >= checkout_baru)
                            $q->whereNot(function ($q2) use ($checkin, $checkout) {
                                $q2->where('waktu_checkout', '<=', $checkin->toDateString())
                                    ->orWhere('waktu_checkin', '>=', $checkout->toDateString());
                            });
                        })
                        ->exists();

                    if ($adaOverlap) {
                        throw new \Exception("Kamar {$kamar->no_kamar} sudah dibooking di rentang tanggal tersebut.");
                    }

                    // 2.c. Hitung subtotal kamar ini
                    $subtotal = $kamar->harga_kamar * $lamaSewa;
                    $totalHargaHeader += $subtotal;

                    $itemsData[] = [
                        'id_kamar'       => $idKamar,
                        'jumlah_pesan'   => 1,
                        'harga'          => $subtotal,     // total harga untuk kamar ini
                        'waktu_checkin'  => $checkin->toDateString(),
                        'waktu_checkout' => $checkout->toDateString(),
                    ];
                }

                // 2.d. Buat header pemesanan (1x saja)
                $idPemesanan = $this->generateKodePemesanan();

                $pemesanan = Pemesanan::create([
                    'id_pemesanan'   => $idPemesanan,
                    'id_penyewa'     => $idPenyewa,
                    'id_cabang'      => $idCabang,
                    'waktu_pemesanan'=> now(),
                    'total_harga'    => $totalHargaHeader,
                    'status'         => 'Belum Dibayar',
                    'expired_at'     => now()->addMinutes(30),
                ]);

                // 2.e. Simpan detail pemesanan_item
                foreach ($itemsData as $item) {
                    $item['id_pemesanan'] = $pemesanan->id_pemesanan;
                    PemesananItem::create($item);
                }

                return $pemesanan;
            });

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['booking' => $e->getMessage()]);
        }

        $pemesanan->load(['items.kamar', 'penyewa']);

        $cabang = $pemesanan->cabang;
        Alert::success('Pemesanan Berhasil Dilakukan!', "Lakukan pembayaran untuk pesanan {$pemesanan->id_pemesanan} segera.");

        return redirect()->route('booking.pembayaran', $pemesanan->id_pemesanan);
    }

    // Generate kode pemesanan: PS00001, PS00002, ...
    private function generateKodePemesanan(): string
    {
        $last = Pemesanan::orderBy('id_pemesanan', 'desc')->first();

        if (!$last) return 'PS00001';

        $num = (int) substr($last->id_pemesanan, 2); // buang "PS"
        $num++;

        return 'PS' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public function payment($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['items.kamar', 'cabang'])->where('id_pemesanan', $id_pemesanan)->firstOrFail();

        // Cek apakah sudah expired?
        if (now() > $pemesanan->expired_at && $pemesanan->status == 'Belum Dibayar') {
            $pemesanan->update(['status' => 'Dibatalkan']);
            $pemesanan->refresh();
        }

        $cabang = $pemesanan->cabang;

        return view('kamar.detail-pesanan', compact('pemesanan', 'cabang'));
    }

    public function checkStatus($id_pemesanan)
    {
        $pemesanan = Pemesanan::find($id_pemesanan);

        if (!$pemesanan) {
            return response()->json(['status' => '404']);
        }

        // Cek Expired di sisi Server (PENTING!)
        if ($pemesanan->status == 'Belum Dibayar' && now() > $pemesanan->expired_at) {
            $pemesanan->update(['status' => 'Dibatalkan']);
            $pemesanan->refresh(); // Refresh data model
        }

        return response()->json([
            'status' => $pemesanan->status,
            // Kirim sisa waktu dalam detik untuk sinkronisasi timer (opsional)
        ]);
    }

    // Riwayat Pesanan
    public function history()
    {
        // Ambil ID Penyewa yang sedang login
        $idPenyewa = Auth::user()->id_penyewa;

        // Ambil data pemesanan, urutkan dari yang terbaru
        $orders = Pemesanan::where('id_penyewa', $idPenyewa)
                    ->with(['cabang']) // Eager load cabang biar bisa tampilkan nama cabang (opsional)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('kamar.riwayat-pesanan', compact('orders'));
    }

    // Membatalkan pesanan
    public function cancel($id_pemesanan)
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id_pemesanan)
                    ->where('id_penyewa', Auth::user()->id_penyewa) // Pastikan milik sendiri
                    ->firstOrFail();

        // Hanya bisa batal kalau statusnya 'Belum Dibayar'
        if ($pemesanan->status == 'Belum Dibayar') {
            $pemesanan->update(['status' => 'Dibatalkan']);

            Alert::success('Dibatalkan', 'Pesanan berhasil dibatalkan.');
        } else {
            Alert::error('Gagal', 'Pesanan tidak dapat dibatalkan.');
        }

        return redirect()->back();
    }
}
