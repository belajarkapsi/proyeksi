<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Service;
use App\Models\Cabang;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;

class BookingController extends Controller
{
    public function checkout(Request $request)
    {
        $rooms = collect();
        $cabang = null;
        $totalBasePrice = 0;
        $isVilla = false;

        $services = collect();
        $totalServicePrice = 0;
        $serviceOnly = false;

        $normalizedIncomingServices = $request->input(
            'services',
            $request->input(
                'selected_services',
                $request->input('service_ids', $request->input('service', []))
            )
        );

        if (!is_array($normalizedIncomingServices)) {
            if ($normalizedIncomingServices === null || $normalizedIncomingServices === '') {
                $normalizedIncomingServices = [];
            } else {
                if (is_string($normalizedIncomingServices) && str_contains($normalizedIncomingServices, ',')) {
                    $normalizedIncomingServices = array_values(
                        array_filter(array_map('trim', explode(',', $normalizedIncomingServices)))
                    );
                } else {
                    $normalizedIncomingServices = (array) $normalizedIncomingServices;
                }
            }
        } else {
            $normalizedIncomingServices = array_values(
                array_filter($normalizedIncomingServices, fn ($v) => $v !== null && $v !== '')
            );
        }

        if ($request->isMethod('post') && $request->has('selected_rooms')) {
            $nos = $request->input('selected_rooms');
            $rooms = Kamar::whereIn('no_kamar', $nos)->get();
        } elseif ($request->has('kamar')) {
            $room = Kamar::where('no_kamar', $request->query('kamar'))->first();

            if (!$room) {
                Alert::error('Gagal', 'Kamar tidak ditemukan!');
                return redirect()->route('dashboard');
            }

            $rooms = collect([$room]);
        } elseif ($request->has('cabang_villa')) {
            $cabangVilla = Cabang::where('id_cabang', $request->input('cabang_villa'))
                ->where('kategori_cabang', 'villa')
                ->first();

            if (!$cabangVilla) {
                Alert::error('Gagal', 'Cabang Villa tidak valid atau tidak ditemukan!');
                return redirect()->route('dashboard');
            }

            $rooms = Kamar::where('id_cabang', $cabangVilla->id_cabang)->get();
            $isVilla = true;

            if ($rooms->isEmpty()) {
                Alert::error('Gagal', 'Villa ditemukan tetapi tidak memiliki kamar untuk dipesan.');
                return redirect()->route('dashboard');
            }
        }

        $rawServices = $request->input('services', $normalizedIncomingServices);
        $selectedServiceIds = [];

        if (!is_array($rawServices) && is_string($rawServices)) {
            $rawServices = str_contains($rawServices, ',')
                ? array_map('trim', explode(',', $rawServices))
                : [$rawServices];
        }

        if (is_array($rawServices)) {
            foreach ($rawServices as $s) {
                if (is_numeric($s) && (int) $s > 0) {
                    $selectedServiceIds[] = (int) $s;
                }
            }
        }

        $selectedServiceIds = array_values(array_unique($selectedServiceIds));
        $qtySource = $request->input('service_quantity', $request->input('service_qty', []));

        if (!empty($selectedServiceIds)) {
            $services = Service::whereIn('id', $selectedServiceIds)->get();

            foreach ($services as $srv) {
                $id = (string) $srv->id;
                $qty = $qtySource[$id] ?? 0;

                $price = (float) ($srv->price ?? $srv->harga ?? 0);
                $totalServicePrice += $price * $qty;

                $srv->requested_qty = $qty;
                $srv->line_total = $price * $qty;
            }
        }

        if ($rooms->isEmpty()) {
            if ($services->isNotEmpty()) {
                $serviceOnly = true;
            } else {
                Alert::error('Gagal', 'Kamar yang kamu pilih telah dipesan.');
                return redirect()->route('dashboard');
            }
        }

        $firstRoom = $rooms->first();

        if ($firstRoom && !$firstRoom->relationLoaded('cabang')) {
            $firstRoom->load('cabang');
        }

        $cabang = $firstRoom?->cabang;
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
    if (!Auth::check()) {
        Alert::error('Eitss', 'Silakan login terlebih dahulu untuk melakukan pemesanan.');
        return redirect()->route('login');
    }

    $rawServiceIds = $request->input('services', []);
    $hasServiceIds = is_array($rawServiceIds)
        ? count(array_filter($rawServiceIds)) > 0
        : !empty($rawServiceIds);

    $serviceOnlyFlag = $request->filled('service_only')
        || ($hasServiceIds && !$request->has('kamar_ids'));

    if (!$hasServiceIds) {
        $detected = [];

        foreach ($request->all() as $k => $v) {
            if (preg_match('/^(srv-check-|svc-|hidden_srv_|service[_-]?id|vis-check-|service[_-]?ids?|selected_services|selected_service)$/i', $k)) {
                if (is_array($v)) {
                    foreach ($v as $item) {
                        if (is_numeric($item) && (int) $item > 0) {
                            $detected[] = (int) $item;
                        }
                    }
                } elseif (is_string($v) && preg_match('/^\d+(,\d+)*$/', $v)) {
                    foreach (explode(',', $v) as $p) {
                        if (is_numeric($p)) {
                            $detected[] = (int) $p;
                        }
                    }
                } elseif (is_numeric($v)) {
                    $detected[] = (int) $v;
                }
            }

            if (preg_match('/^service[_-]?(\d+)$/i', $k, $m)) {
                $detected[] = (int) $m[1];
            }
        }

        foreach ($request->all() as $k => $v) {
            if (preg_match('/^vis-check-(\d+)$/', $k, $m)) {
                $detected[] = (int) $m[1];
            }
        }

        $detected = array_unique(array_filter($detected));

        if (count($detected) > 0) {
            $rawServiceIds = $detected;
            $hasServiceIds = true;

            if (!$request->has('kamar_ids')) {
                $serviceOnlyFlag = true;
            }

            \Log::debug('Booking.store detected fallback service ids', ['detected' => $detected]);
        }
    }

    $rules = [
        'nama_lengkap'         => 'required|string|max:255',
        'telepon'              => 'required|numeric',
        'email'                => 'required|email',
        'tanggal'              => 'nullable|date',
        'check_in'             => 'nullable|date',
        'check_out'            => 'nullable|date|after:check_in',
        'services'             => 'nullable|array',
        'services.*'           => 'nullable|integer|exists:services,id',
        'service_quantity'     => 'nullable|array',
        'service_quantity.*'   => 'nullable|integer|min:0',
    ];

    if (!$serviceOnlyFlag) {
        $rules['kamar_ids'] = 'required|array|min:1';
        $rules['kamar_ids.*'] = 'exists:kamar,id_kamar';
        $rules['durasi'] = 'required|array';
        $rules['durasi.*'] = 'integer|min:1';
    } else {
        $rules['kamar_ids'] = 'nullable|array';
        $rules['durasi'] = 'nullable|array';
        $rules['durasi.*'] = 'nullable|integer|min:1';
    }

    $validated = $request->validate($rules);

    $kamarIds = isset($validated['kamar_ids'])
        ? array_values(array_unique($validated['kamar_ids']))
        : [];

    $kamars = collect();

    if (!empty($kamarIds)) {
        $kamars = Kamar::whereIn('id_kamar', $kamarIds)->get();

        if ($kamars->count() !== count($kamarIds)) {
            return back()->withErrors(['booking' => 'Sebagian kamar tidak ditemukan.']);
        }
    }

    if (!empty($validated['tanggal'])) {
        $tanggalCheckin = Carbon::parse($validated['tanggal'])->startOfDay();
    } elseif ($request->filled('check_in')) {
        $tanggalCheckin = Carbon::parse($request->input('check_in'))->startOfDay();
    } else {
        return back()->withErrors(['booking' => 'Tanggal check-in tidak ditemukan.']);
    }

    $idPenyewa = Auth::user()->id_penyewa ?? Auth::id();
    $idCabang  = $kamars->first()->id_cabang ?? null;

    $serviceIds  = $validated['services'] ?? $rawServiceIds ?? [];
    $serviceQtys = $validated['service_quantity'] ?? $request->input('service_quantity', []);

    foreach ($request->all() as $k => $v) {
        if (preg_match('/(?:^|_)(?:qty|quantity|jumlah)[-_]?(\d+)$/i', $k, $m)) {
            if (!isset($serviceQtys[$m[1]])) {
                $serviceQtys[$m[1]] = (int) $v;
            }
        }
    }

    $serviceIds = array_unique(array_map('intval', (array) $serviceIds));

    $serviceTotal = 0;
    $servicesCollection = Service::whereIn('id', $serviceIds)->get();

    foreach ($servicesCollection as $svc) {
        $qty = $serviceQtys[$svc->id] ?? 1;
        $serviceTotal += $svc->price * $qty;
    }

    try {
        $pemesanan = DB::transaction(function () use (
            $kamarIds,
            $kamars,
            $validated,
            $tanggalCheckin,
            $idPenyewa,
            $idCabang,
            $serviceTotal,
            $serviceIds,
            $serviceQtys,
            $servicesCollection
        ) {
            $totalHargaHeader = 0;
            $itemsData = [];

            foreach ($kamarIds as $idKamar) {
                $kamar = $kamars->firstWhere('id_kamar', $idKamar);
                $lamaSewa = (int) $validated['durasi'][$idKamar];

                $checkin  = clone $tanggalCheckin;
                $checkout = (clone $tanggalCheckin)->addDays($lamaSewa);

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

            $pemesanan = Pemesanan::create([
                'id_pemesanan'    => $this->generateKodePemesanan(),
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

            foreach ($servicesCollection as $svc) {
                DB::table('pemesanan_service')->insert([
                    'id_pemesanan' => $pemesanan->id_pemesanan,
                    'id_service'   => $svc->id,
                    'quantity'     => $serviceQtys[$svc->id] ?? 1,
                    'price'        => $svc->price,
                    'subtotal'     => $svc->price * ($serviceQtys[$svc->id] ?? 1),
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            return $pemesanan;
        });
    } catch (\Exception $e) {
        \Log::error($e->getMessage());
        return back()->withErrors(['booking' => $e->getMessage()]);
    }

    Alert::success('Berhasil', 'Pemesanan berhasil dibuat.');
    return redirect()->route('booking.pembayaran', $pemesanan->id_pemesanan);
}


    private function generateKodePemesanan(): string
    {
        $last = Pemesanan::orderBy('id_pemesanan', 'desc')->first();

        if (!$last) {
            return 'PS00001';
        }

        $num = (int) substr($last->id_pemesanan, 2);
        $num++;

        return 'PS' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public function payment($id_pemesanan)
    {
        $pemesanan = Pemesanan::with(['items.kamar', 'cabang'])
            ->where('id_pemesanan', $id_pemesanan)
            ->firstOrFail();

        if (now()->greaterThan($pemesanan->expired_at) && $pemesanan->status === 'Belum Dibayar') {
            $pemesanan->update(['status' => 'Dibatalkan']);
            $pemesanan->refresh();
        }

        return view('kamar.detail-pesanan', [
            'pemesanan' => $pemesanan,
            'cabang' => $pemesanan->cabang,
        ]);
    }

    public function checkStatus($id_pemesanan)
    {
        $pemesanan = Pemesanan::find($id_pemesanan);

        if (!$pemesanan) {
            return response()->json(['status' => '404']);
        }

        if ($pemesanan->status === 'Belum Dibayar' && now()->greaterThan($pemesanan->expired_at)) {
            $pemesanan->update(['status' => 'Dibatalkan']);
            $pemesanan->refresh();
        }

        return response()->json([
            'status' => $pemesanan->status,
        ]);
    }

    public function history()
    {
        $orders = Pemesanan::where('id_penyewa', Auth::user()->id_penyewa)
            ->with('cabang')
            ->latest()
            ->get();

        return view('kamar.riwayat-pesanan', compact('orders'));
    }

    public function cancel($id_pemesanan)
    {
        $pemesanan = Pemesanan::where('id_pemesanan', $id_pemesanan)
            ->where('id_penyewa', Auth::user()->id_penyewa)
            ->firstOrFail();

        if ($pemesanan->status !== 'Belum Dibayar') {
            Alert::error('Gagal', 'Pesanan tidak dapat dibatalkan.');
            return back();
        }

        try {
            BookingService::cancelBooking($pemesanan->id_pemesanan);
            $pemesanan->refresh();

            Alert::success('Dibatalkan', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan.');
        }

        return back();
    }
}
