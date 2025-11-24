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

    dd([
        'STATUS' => '✅ SUKSES! Data berhasil masuk ke Database',

        '1. INFO HEADER PESANAN (Tabel Pemesanan)' => [
            'ID Pemesanan' => $pemesanan->id_pemesanan,
            'Tanggal Pesan' => $pemesanan->waktu_pemesanan,
            'Total Harga' => 'Rp ' . number_format($pemesanan->total_harga, 0, ',', '.'),
            'Status' => $pemesanan->status,
        ],

        '2. SIAPA YANG PESAN? (Cek Relasi ke User)' => [
            'ID Penyewa (Foreign Key)' => $pemesanan->id_penyewa,
            'Nama di Tabel User' => $pemesanan->penyewa->nama_lengkap,
            'Email' => $pemesanan->penyewa->email,
            'Note' => 'Jika nama ini muncul, berarti relasi ke tabel user AMAN (Tidak Duplikat).'
        ],

        '3. DETAIL KAMAR (Tabel Pemesanan Item)' => $pemesanan->items->map(function($item) {
            return [
                'Nomor Kamar' => $item->kamar->no_kamar,
                'Tipe Kamar' => $item->kamar->tipe_kamar,
                'Check In' => $item->waktu_checkin->format('Y-m-d'),
                'Check Out' => $item->waktu_checkout->format('Y-m-d'),
                'Harga Subtotal' => $item->harga
            ];
        })->toArray(),
    ]);

        $cabang = $pemesanan->cabang;

        Alert::success('Berhasil', "Pemesanan {$pemesanan->id_pemesanan} berhasil dibuat.");

        return redirect()->route('cabang.kamar.index', $cabang->route_params);
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
}
