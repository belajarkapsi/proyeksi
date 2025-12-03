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

public function checkout(Request $request)
{
    // === 1. INISIALISASI VARIABEL GLOBAL ===
    $rooms = collect();
    $cabang = null;
    $totalBasePrice = 0;
    $isVilla = false; // Set default mode

    // Service-related
    $services = collect();
    $totalServicePrice = 0;
    $serviceOnly = false;

    $normalizedIncomingServices = $request->input('services',
        $request->input('selected_services',
            $request->input('service_ids',
                $request->input('service', []))));

    if (!is_array($normalizedIncomingServices)) {
        if ($normalizedIncomingServices === null || $normalizedIncomingServices === '') {
            $normalizedIncomingServices = [];
        } else {
            if (is_string($normalizedIncomingServices) && strpos($normalizedIncomingServices, ',') !== false) {
                $normalizedIncomingServices = array_values(array_filter(array_map('trim', explode(',', $normalizedIncomingServices))));
            } else {
                $normalizedIncomingServices = (array) $normalizedIncomingServices;
            }
        }
    } else {
        $normalizedIncomingServices = array_values(array_filter($normalizedIncomingServices, fn($v) => $v !== null && $v !== ''));
    }

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
    // 4. LOGIKA VILLA (Mode Pemesanan Berdasarkan Cabang Villa)
    else if ($request->has('cabang_villa')) {
        $cabangId = $request->input('cabang_villa');

        $cabangVilla = Cabang::where('id_cabang', $cabangId)
                            ->where('kategori_cabang', 'villa')
                            ->first();

        if (!$cabangVilla) {
            Alert::error('Gagal', 'Cabang Villa tidak valid atau tidak ditemukan!');
            return redirect()->route('dashboard');
        }

        $rooms = Kamar::where('id_cabang', $cabangId)->get();
        $isVilla = true;

        if ($rooms->isEmpty()) {
            Alert::error('Gagal', 'Villa ditemukan tetapi tidak memiliki kamar untuk dipesan.');
            return redirect()->route('dashboard');
        }
    }

    // === SERVICE DETECTION (robust) ===
    // Ambil services[] langsung (fallback ke normalizedIncomingServices jika request tidak punya key 'services')
    $rawServices = $request->input('services', $normalizedIncomingServices);

    // Normalisasi menjadi array numeric
    $selectedServiceIds = [];
    if (!is_array($rawServices) && is_string($rawServices)) {
        $rawServices = trim($rawServices);
        if ($rawServices === '') $rawServices = [];
        elseif (strpos($rawServices, ',') !== false) $rawServices = array_map('trim', explode(',', $rawServices));
        else $rawServices = [$rawServices];
    }

    if (is_array($rawServices)) {
        foreach ($rawServices as $s) {
            if (is_numeric($s) && (int)$s > 0) $selectedServiceIds[] = (int)$s;
        }
    }

    $selectedServiceIds = array_values(array_unique($selectedServiceIds));

    // Ambil qty sources (bisa service_quantity[<id>] atau service_qty[<id>])
    $qtySource = $request->input('service_quantity', $request->input('service_qty', []));

    if (!empty($selectedServiceIds)) {
        $services = Service::whereIn('id', $selectedServiceIds)->get();

        foreach ($services as $srv) {
            $id = (string)$srv->id;
            $qty = 0;

            if (is_array($qtySource) && array_key_exists($id, $qtySource)) {
                $qRaw = $qtySource[$id];
                $q = intval($qRaw);
                if ($q > 0) $qty = $q;
            } else {
                $altKey = 'service_quantity_' . $id;
                if ($request->has($altKey)) {
                    $q = intval($request->input($altKey, 0));
                    if ($q > 0) $qty = $q;
                }
            }

            $price = floatval($srv->price ?? $srv->harga ?? 0);
            $totalServicePrice += ($price * $qty);

            $srv->requested_qty = $qty;
            $srv->line_total = $price * $qty;
        }
    }

    // 5. Akses tanpa data (Jika $rooms masih kosong setelah semua cek di atas)
    if ($rooms->isEmpty()) {
        // Jika tidak ada kamar tapi ada layanan -> izinkan (service-only)
        if ($services->isNotEmpty()) {
            $serviceOnly = true;
            // lanjut ke view tanpa redirect — view akan menampilkan panel layanan
        } else {
            // original behavior preserved
            Alert::error('Gagal', 'Kamar yang kamu pilih telah dipesan, Silahkan pilih kamar lain.');
            return redirect()->route('dashboard');
        }
    }

    // === 6. FINAL DATA PREPARATION ===
    $firstRoom = $rooms->first();

    if ($firstRoom && !$firstRoom->relationLoaded('cabang')) {
        $firstRoom->load('cabang');
    }

    $cabang = $firstRoom ? $firstRoom->cabang : null;
    $totalBasePrice = $rooms->sum('harga_kamar');

    return view('kamar.booking', compact(
        'rooms',
        'totalBasePrice',
        'cabang',
        'isVilla',
        'services',
        'totalServicePrice',
        'serviceOnly'
    ));
}

public function store(Request $request)
{
    // Pastikan user login
    if (!Auth::check()) {
        Alert::error('Eitss', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
        return redirect()->route('login');
    }

    // DETEKSI SERVICE-ONLY (lebih robust)
    $rawServiceIds = $request->input('services', []);
    $hasServiceIds = is_array($rawServiceIds) ? count(array_filter($rawServiceIds)) > 0 : (!empty($rawServiceIds));
    $serviceOnlyFlag = $request->filled('service_only') || ($hasServiceIds && !$request->has('kamar_ids'));

    // Jika tidak ada services[] tetapi ada checkbox/visible ids/hidden_srv_*, coba deteksi dari pola lain
    if (!$hasServiceIds) {
// ----- fallback detection (lebih ketat) -----
$detected = [];

// 1) Ambil dari keys yang secara eksplisit menunjukkan service
foreach ($request->all() as $k => $v) {
    // pola-pola yang khusus untuk service
    if (preg_match('/^(srv-check-|svc-|hidden_srv_|service[_-]?id|vis-check-|service[_-]?ids?|selected_services|selected_service)$/i', $k)) {
        if (is_array($v)) {
            foreach ($v as $item) {
                if (is_numeric($item) && (int)$item > 0) $detected[] = (int)$item;
            }
        } elseif (is_string($v) && preg_match('/^\d+(,\d+)*$/', $v)) {
            $parts = array_filter(array_map('trim', explode(',', $v)));
            foreach ($parts as $p) if (is_numeric($p) && (int)$p > 0) $detected[] = (int)$p;
        } elseif (is_numeric($v) && (int)$v > 0) {
            $detected[] = (int)$v;
        }
    }

    // juga deteksi pola key yang mengandung "service" + id di nama key (mis: service_12 = on)
    if (preg_match('/^service[_-]?(\d+)$/i', $k, $m)) {
        $detected[] = (int)$m[1];
    }
}

// 2) Jika masih kosong, coba pola checkbox-like "vis-check-<id>"
foreach ($request->all() as $k => $v) {
    if (preg_match('/^vis-check-(\d+)$/', $k, $m)) {
        $detected[] = (int)$m[1];
    }
}
        $detected = array_unique(array_filter($detected, function($x){ return is_numeric($x) && (int)$x > 0; }));
        if (count($detected) > 0) {
            $rawServiceIds = $detected;
            $hasServiceIds = true;
            if (!$request->has('kamar_ids')) $serviceOnlyFlag = true;
            \Log::debug('Booking.store detected fallback service ids', ['detected' => $detected]);
        }
    }
    $rules = [
        'nama_lengkap' => 'required|string|max:255',
        'telepon'      => 'required|numeric',
        'email'        => 'required|email',
        'tanggal'      => 'nullable|date',
        'check_in'     => 'nullable|date',
        'check_out'    => 'nullable|date|after:check_in',
        'services'             => 'nullable|array',
        'services.*'           => 'nullable|integer|exists:services,id',
        'service_quantity'     => 'nullable|array',
        'service_quantity.*'   => 'nullable|integer|min:0',
    ];

    if (!$serviceOnlyFlag) {
        $rules['kamar_ids']   = 'required|array|min:1';
        $rules['kamar_ids.*'] = 'exists:kamar,id_kamar';
        $rules['durasi']      = 'required|array';
        $rules['durasi.*']    = 'integer|min:1';
    } else {
        $rules['kamar_ids'] = 'nullable|array';
        $rules['durasi'] = 'nullable|array';
        $rules['durasi.*'] = 'nullable|integer|min:1';
    }

    $validated = $request->validate($rules);
    // Normalize kamar ids
    $kamarIds = isset($validated['kamar_ids']) && is_array($validated['kamar_ids'])
                ? array_values(array_unique($validated['kamar_ids']))
                : [];
    // Ambil kamar hanya jika ada kamarIds
    $kamars = collect();
    if (!empty($kamarIds)) {
        $kamars = Kamar::whereIn('id_kamar', $kamarIds)->get();
        if ($kamars->count() !== count($kamarIds)) {
            return back()->withInput()->withErrors(['booking' => 'Sebagian kamar tidak ditemukan.']);
        }
    }
    // Tanggal check-in
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

    // ----- Service totals: prefer validated services[] otherwise fallback to earlier-detected rawServiceIds -----
    $serviceIds = $validated['services'] ?? $rawServiceIds ?? [];
    $serviceQtys = $validated['service_quantity'] ?? $request->input('service_quantity', []);

    // fallback qty extraction: check for keys like qty-<id>, hidden_qty_<id>, qty_<id>
    if (empty($serviceQtys) || !is_array($serviceQtys)) $serviceQtys = [];

    // Attempt to detect qty from request keys if none provided
    foreach ($request->all() as $k => $v) {
        if (preg_match('/(?:^|_)(?:qty|quantity|jumlah)[-_]?(\d+)$/i', $k, $m)) {
            $id = (int)$m[1];
            if (!isset($serviceQtys[$id]) && is_numeric($v)) {
                $serviceQtys[$id] = (int)$v;
            }
        }
        if (preg_match('/^hidden_qty[_-]?(\d+)$/i', $k, $m)) {
            $id = (int)$m[1];
            if (!isset($serviceQtys[$id]) && is_numeric($v)) {
                $serviceQtys[$id] = (int)$v;
            }
        }
        if (preg_match('/^qty[_-]?(\d+)$/i', $k, $m)) {
            $id = (int)$m[1];
            if (!isset($serviceQtys[$id]) && is_numeric($v)) {
                $serviceQtys[$id] = (int)$v;
            }
        }
    }
    // Clean serviceIds
    if (is_array($serviceIds)) {
        $serviceIds = array_values(array_filter(array_map(function($x){ return is_numeric($x) ? (int)$x : null; }, $serviceIds)));
    } elseif (is_string($serviceIds) && strpos($serviceIds, ',') !== false) {
        $serviceIds = array_values(array_map('intval', array_filter(array_map('trim', explode(',', $serviceIds)))));
    } else {
        $serviceIds = is_numeric($serviceIds) ? [(int)$serviceIds] : [];
    }

    $serviceIds = array_unique($serviceIds);
    // Load service models
    $serviceTotal = 0;
    $servicesCollection = collect();
    if (!empty($serviceIds)) {
        $servicesCollection = Service::whereIn('id', $serviceIds)->get();
        foreach ($servicesCollection as $svc) {
            $qty = isset($serviceQtys[$svc->id]) ? max(0, (int)$serviceQtys[$svc->id]) : 1;
            $serviceTotal += ($svc->price * $qty);
        }
    }

    // Proceed DB transaction (same as before), but lock rooms only if kamarIds present
    try {
        $pemesanan = DB::transaction(function () use (
            $kamarIds, $kamars, $validated, $tanggalCheckin, $idPenyewa, $idCabang,
            $serviceTotal, $serviceIds, $serviceQtys, $servicesCollection
        ) {
            if (!empty($kamarIds)) {
                DB::table('kamar')->whereIn('id_kamar', $kamarIds)->lockForUpdate()->get();
            }

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

                $adaOverlap = DB::table((new PemesananItem)->getTable() . ' as pi')
                    ->join((new Pemesanan)->getTable() . ' as p', 'pi.id_pemesanan', '=', 'p.id_pemesanan')
                    ->where('pi.id_kamar', $idKamar)
                    ->whereNotIn('p.status', ['Dibatalkan', 'Canceled'])
                    ->where(function ($q) use ($checkin, $checkout) {
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
            $grandTotal = $totalHargaHeader + $serviceTotal;
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

            foreach ($itemsData as $item) {
                $item['id_pemesanan'] = $pemesanan->id_pemesanan;
                PemesananItem::create($item);
            }

            if (!empty($serviceIds) && Schema::hasTable('pemesanan_service')) {
                foreach ($servicesCollection as $svc) {
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
                if (Schema::hasColumn('pemesanan', 'meta')) {
                    $meta = $pemesanan->meta ? json_decode($pemesanan->meta, true) : [];
                    $meta['services'] = [];
                    foreach ($servicesCollection as $svc) {
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
                    \Log::info("Pemesanan {$pemesanan->id_pemesanan} - services chosen but no pivot table found.", [
                        'services' => $serviceIds, 'quantities' => $serviceQtys
                    ]);
                }
            }

            return $pemesanan;
        });

    } catch (\Exception $e) {
        \Log::error('Booking.store exception: '.$e->getMessage(), [
            'user_id' => Auth::id(), 'exception' => $e
        ]);
        return back()->withInput()->withErrors(['booking' => $e->getMessage()]);
    }
    $pemesanan->load(['items.kamar', 'penyewa']);
    Alert::success('Pemesanan Berhasil Dilakukan!', "Lakukan pembayaran untuk pesanan {$pemesanan->id_pemesanan} segera.");
    return redirect()->route('booking.pembayaran', $pemesanan->id_pemesanan);
}

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