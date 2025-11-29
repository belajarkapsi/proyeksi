<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\PemesananService;
use App\Models\Cabang;
use App\Models\Service;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{
   // Jembatan ke halaman checkout (form booking)
    public function checkout(Request $request)
    {
        // === 1. INISIALISASI VARIABEL GLOBAL ===
        $rooms = collect();
        $cabang = null;
        $totalBasePrice = 0;
        $isVilla = false; // Set default mode

        // 2. Dari form multi-select kamar (POST) - Biasanya untuk Kost/Hotel
        if ($request->isMethod('post') && $request->has('selected_rooms')) {
            $nos = $request->input('selected_rooms'); // diasumsikan berisi no_kamar
            $rooms = Kamar::whereIn('no_kamar', $nos)->get();
        }
        
        // 3. Dari klik cepat single (GET ?kamar=xxx) - Biasanya untuk Kost/Hotel
        else if ($request->has('kamar')) {
            $noKamar = $request->query('kamar');
            $room = Kamar::where('no_kamar', $noKamar)->first();

            if (!$room) {
                Alert::error('Gagal', 'Kamar tidak ditemukan!');
                return redirect()->route('dashboard');
            }
            $rooms = collect([$room]);
        }
        
        // 4. 🚀 LOGIKA VILLA (Mode Pemesanan Berdasarkan Cabang Villa) 🚀
        // Asumsikan form/link Villa mengirimkan ID Cabang (id_cabang)
        else if ($request->has('cabang_villa')) {
            $cabangId = $request->input('cabang_villa');

            // Cek apakah ID cabang itu ada dan kategorinya benar-benar 'villa'
            $cabangVilla = Cabang::where('id_cabang', $cabangId)
                                ->where('kategori_cabang', 'villa')
                                ->first();

            if (!$cabangVilla) {
                Alert::error('Gagal', 'Cabang Villa tidak valid atau tidak ditemukan!');
                return redirect()->route('dashboard');
            }

            // Ambil SEMUA kamar yang terasosiasi dengan cabang Villa ini
            $rooms = Kamar::where('id_cabang', $cabangId)->get();
            $isVilla = true; // Aktifkan mode Villa

            if ($rooms->isEmpty()) {
                Alert::error('Gagal', 'Villa ditemukan tetapi tidak memiliki kamar untuk dipesan.');
                return redirect()->route('dashboard');
            }
        }
        
        // 5. Akses tanpa data (Jika $rooms masih kosong setelah semua cek di atas)
        if ($rooms->isEmpty()) {
            Alert::error('Gagal', 'Silakan pilih kamar terlebih dahulu sebelum memesan.');
            return redirect()->route('dashboard');
        }
        
        // === 6. FINAL DATA PREPARATION ===
        $firstRoom = $rooms->first();

        // Eager load cabang (sudah ada di kode lama Anda)
        if ($firstRoom && !$firstRoom->relationLoaded('cabang')) {
            $firstRoom->load('cabang');
        }

        $cabang = $firstRoom ? $firstRoom->cabang : null;

        // Hitung total harga dasar (per malam)
        $totalBasePrice = $rooms->sum('harga_kamar');

        return view('kamar.booking', compact('rooms', 'totalBasePrice', 'cabang', 'isVilla'));
    }

    // Simpan pemesanan (header + detail) + lock + cek overlap
    public function store(Request $request)
    {
        // Pastikan user login
        if (!Auth::check()) {
            Alert::error('Eitss', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
            return redirect()->route('login');
        }

        // Validasi input (memperbolehkan juga check_in/check_out yang dikirim dari flow villa)
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'telepon'      => 'required|numeric',
            'email'        => 'required|email',
            // support both single tanggal (legacy) or check_in/check_out (villa)
            'tanggal'      => 'nullable|date',
            'check_in'     => 'nullable|date',
            'check_out'    => 'nullable|date|after:check_in',

            'kamar_ids'    => 'required|array|min:1',
            'kamar_ids.*'  => 'exists:kamar,id_kamar',

            'durasi'       => 'required|array',
            'durasi.*'     => 'integer|min:1',

            'services'             => 'nullable|array',
            'services.*'           => 'nullable|integer|exists:services,id',
            'service_quantity'     => 'nullable|array',
            'service_quantity.*'   => 'nullable|integer|min:0',
        ]);

        // Normalize kamar ids (hapus duplikat)
        $kamarIds = array_values(array_unique($validated['kamar_ids']));
        $kamars = Kamar::whereIn('id_kamar', $kamarIds)->get();

        if ($kamars->count() !== count($kamarIds)) {
            return back()->withInput()->withErrors(['booking' => 'Sebagian kamar tidak ditemukan.']);
        }

        // Use tanggal (legacy) or check_in fallback
        $tanggalCheckin = null;
        if (!empty($validated['tanggal'])) {
            $tanggalCheckin = Carbon::parse($validated['tanggal'])->startOfDay();
        } elseif ($request->filled('check_in')) {
            $tanggalCheckin = Carbon::parse($request->input('check_in'))->startOfDay();
        } else {
            return back()->withInput()->withErrors(['booking' => 'Tanggal check-in tidak ditemukan.']);
        }

        $idPenyewa = Auth::user()->id_penyewa ?? Auth::id();
        $idCabang = $kamars->first()->id_cabang ?? null;

        // Calculate service totals (if any)
        $serviceIds = $request->input('services', []);
        $serviceQtys = $request->input('service_quantity', []);
        $serviceTotal = 0;
        $services = collect();

        if (!empty($serviceIds)) {
            $services = Service::whereIn('id', $serviceIds)->get();
            foreach ($services as $svc) {
                $qty = isset($serviceQtys[$svc->id]) ? (int)$serviceQtys[$svc->id] : 1;
                if ($qty < 0) $qty = 0;
                $serviceTotal += ($svc->price * $qty);
            }
        }

        try {
            $pemesanan = DB::transaction(function () use (
                $kamarIds, $kamars, $validated, $tanggalCheckin, $idPenyewa, $idCabang,
                $serviceTotal, $serviceIds, $serviceQtys, $services
            ) {
                // Lock kamar rows
                DB::table('kamar')->whereIn('id_kamar', $kamarIds)->lockForUpdate()->get();

                $totalHargaHeader = 0;
                $itemsData = [];

                foreach ($kamarIds as $idKamar) {
                    $kamar = $kamars->firstWhere('id_kamar', $idKamar);
                    if (!$kamar) throw new \Exception("Kamar dengan ID $idKamar tidak ditemukan.");

                    if (!isset($validated['durasi'][$idKamar])) {
                        throw new \Exception("Durasi sewa untuk kamar {$kamar->no_kamar} belum diisi.");
                    }

                    $lamaSewa = (int) $validated['durasi'][$idKamar];
                    $checkin  = clone $tanggalCheckin;
                    $checkout = (clone $tanggalCheckin)->addDays($lamaSewa);

                    // Overlap check
                    // **Perubahan minimal:** exclude pemesanan yang berstatus 'Dibatalkan' agar pemesanan batal
                    // tidak memblokir pemesanan baru. Logika overlap lain tetap sama.
                    $adaOverlap = DB::table((new PemesananItem)->getTable() . ' as pi')
                        ->join((new Pemesanan)->getTable() . ' as p', 'pi.id_pemesanan', '=', 'p.id_pemesanan')
                        ->where('pi.id_kamar', $idKamar)
                        ->whereNotIn('p.status', ['Dibatalkan', 'Canceled']) // exclude canceled
                        ->where(function ($q) use ($checkin, $checkout) {
                            // kondisi overlap lama: NOT (checkout <= checkin OR checkin >= checkout)
                            $q->whereNot(function ($q2) use ($checkin, $checkout) {
                                $q2->where('pi.waktu_checkout', '<=', $checkin->toDateString())
                                    ->orWhere('pi.waktu_checkin', '>=', $checkout->toDateString());
                            });
                        })->exists();

                    if ($adaOverlap) {
                        throw new \Exception("Kamar {$kamar->no_kamar} sudah dibooking di rentang tanggal tersebut.");
                    }

                    $subtotal = $kamar->harga_kamar * $lamaSewa;
                    $totalHargaHeader += $subtotal;

                    $itemsData[] = [
                        'id_kamar'       => $idKamar,
                        'jumlah_pesan'   => 1,
                        'harga'          => $subtotal,
                        'waktu_checkin'  => $checkin->toDateString(),
                        'waktu_checkout' => $checkout->toDateString(),
                    ];
                }

                // Tambahkan service total ke header total
                $grandTotal = $totalHargaHeader + $serviceTotal;

                // Create pemesanan header
                $idPemesanan = $this->generateKodePemesanan();
                $pemesanan = Pemesanan::create([
                    'id_pemesanan'    => $idPemesanan,
                    'id_penyewa'      => $idPenyewa,
                    'id_cabang'       => $idCabang,
                    'waktu_pemesanan' => now(),
                    'total_harga'     => $grandTotal,
                    'status'          => 'Belum Dibayar',
                    'expired_at'      => now()->addMinutes(30),
                ]);

                // save pemesanan items (rooms)
                foreach ($itemsData as $item) {
                    $item['id_pemesanan'] = $pemesanan->id_pemesanan;
                    PemesananItem::create($item);
                }

                // Save services if table exists (pemesanan_service)
                if (!empty($serviceIds) && Schema::hasTable('pemesanan_service')) {
                    foreach ($services as $svc) {
                        $qty = isset($serviceQtys[$svc->id]) ? (int)$serviceQtys[$svc->id] : 1;
                        if ($qty <= 0) continue;

                        DB::table('pemesanan_service')->insert([
                            'id_pemesanan' => $pemesanan->id_pemesanan,
                            'id_service'   => $svc->id,
                            'quantity'     => $qty,
                            'price'        => $svc->price,
                            'subtotal'     => $svc->price * $qty,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ]);
                    }
                } else {
                    // jika tidak ada tabel pivot, simpan ringkasan ke kolom 'meta' jika tersedia
                    if (Schema::hasColumn('pemesanan', 'meta')) {
                        $meta = $pemesanan->meta ? json_decode($pemesanan->meta, true) : [];
                        $meta['services'] = [];
                        foreach ($services as $svc) {
                            $qty = isset($serviceQtys[$svc->id]) ? (int)$serviceQtys[$svc->id] : 1;
                            if ($qty <= 0) continue;
                            $meta['services'][] = [
                                'id' => $svc->id,
                                'name' => $svc->name,
                                'price' => $svc->price,
                                'quantity' => $qty,
                                'subtotal' => $svc->price * $qty,
                            ];
                        }
                        $pemesanan->update(['meta' => json_encode($meta)]);
                    } else {
                        // fallback: log the chosen services so admin/dev bisa cek
                        \Log::info("Pemesanan {$pemesanan->id_pemesanan} - services chosen but no pivot table found.", [
                            'services' => $serviceIds, 'quantities' => $serviceQtys
                        ]);
                    }
                }

                return $pemesanan;
            });

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['booking' => $e->getMessage()]);
        }

        $pemesanan->load(['items.kamar', 'penyewa']);
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

    // Membatalkan pesanan (menggunakan BookingService agar release kamar dilakukan aman)
    public function cancel($id_pemesanan)
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id_pemesanan)
                    ->where('id_penyewa', Auth::user()->id_penyewa) // Pastikan milik sendiri
                    ->firstOrFail();

        // Hanya bisa batal kalau statusnya 'Belum Dibayar'
        if ($pemesanan->status != 'Belum Dibayar') {
            Alert::error('Gagal', 'Pesanan tidak dapat dibatalkan.');
            return redirect()->back();
        }

        try {
            $result = BookingService::cancelBooking($pemesanan->id_pemesanan);

            if ($result instanceof \Illuminate\Database\Eloquent\Model) {
                $pemesanan = $result;
            } else {
                $pemesanan->refresh();
            }

            Alert::success('Dibatalkan', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            \Log::error("Cancel booking failed for {$id_pemesanan}: " . $e->getMessage(), [
                'id_pemesanan' => $id_pemesanan,
                'user' => Auth::id()
            ]);

            Alert::error('Gagal', 'Terjadi kesalahan saat membatalkan pesanan: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}
