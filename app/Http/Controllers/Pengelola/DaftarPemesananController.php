<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Kamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\PemesananService;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class DaftarPemesananController extends Controller
{
    public function index(Request $request)
    {
        $pengelola = Auth::user();
        if (!$pengelola->cabang) {
            abort(403, 'Akun Anda tidak terhubung dengan cabang manapun.');
        }

        $idCabang = $pengelola->cabang->id_cabang;
        $query = Pemesanan::query()
            ->where('id_cabang', $idCabang)
            ->orderBy('id_pemesanan', 'asc')
            ->with(['penyewa', 'items.kamar', 'service.service', 'cabang', 'pembayaran']);

        if ($request->has('q') && $request->q != '') {
            $keyword = $request->q;
            $query->where(function($q) use ($keyword) {
                $q->where('id_pemesanan', 'like', "%{$keyword}%")
                    ->orWhereHas('penyewa', function($subQ) use ($keyword) {
                        $subQ->where('username', 'like', "%{$keyword}%")
                            ->orWhere('nama_lengkap', 'like', "%{$keyword}%");
                });
            });
        }

        if ($request->has('status') && $request->status != '') {
            if($request->status == 'perlu_verifikasi') {
                $query->where('status', 'Belum Dibayar')->whereHas('pembayaran');
            } else{
                $query->where('status', $request->status);
            }
        }

        $pemesanan = $query->latest()->paginate(10);

        return view('pengelola.daftar-pemesanan.index', [
            'pemesanan' => $pemesanan,
            'q' => $request->q,
            'status' => $request->status
        ]);
    }

    public function checkPenyewa(Request $request)
    {
        $no_hp = $request->query('no_hp');
        $user = User::where('no_telp', $no_hp)->first();

    if ($user) {
        return response()->json([
            'exists' => true,
            'nama' => $user->nama_lengkap
        ]);
    }

    return response()->json(['exists' => false]);
    }

    public function create()
    {
        $pengelola = Auth::user();

        if (!$pengelola->cabang) {
            abort(403, 'Anda tidak memiliki akses cabang ini!');
        }

        $idCabang = $pengelola->cabang->id_cabang;

        // ambil kamar di cabang pengelola
        $kamars = Kamar::where('id_cabang', $idCabang)
                        ->where('status', 'Tersedia')
                        ->orderBy('no_kamar', 'asc')->get();

        // ambil service di cabang pengelola (jika villa)
        $services = Service::where('id_cabang', $idCabang)
                        ->where('stock', '>', 0)->get();

        return view('pengelola.daftar-pemesanan.create', compact('kamars', 'services'));
    }

    public function store(Request $request)
    {
        $pengelola = Auth::user();
        $idCabang = $pengelola->cabang->id_cabang;

        // Validasi data dulu
        $request->validate([
            'nama_tamu'         => 'required|string|max:255',
            'no_hp'             => 'required|numeric',
            'id_kamar'          => 'required|exists:kamar,id_kamar',
            'tanggal_masuk'     => 'required|date|after_or_equal:today',
            'durasi'            => 'required|integer|min:1',
            'status_bayar'      => 'required|in:Lunas,Belum Dibayar',
            'services'          => 'nullable|array',
            'service_qty'       => 'nullable|array',
        ]);

        // Cek validitas Kamar
        $kamar = Kamar::where('id_kamar', $request->id_kamar)
                    ->where('id_cabang', $idCabang)->firstOrFail();

        // Cek Ketersediaan Kamar
        $checkin  = Carbon::parse($request->tanggal_masuk);
        $checkout = $checkin->copy()->addDays((int) $request->durasi);

        $isConflict = Pemesanan::roomHasConflict(
            $kamar->id_kamar,
            $checkin->format('Y-m-d'),
            $checkout->format('Y-m-d')
        );
        if($isConflict) {
            return back()->withInput()->with('error', 'Kamar sudah ada yang pesan di rentan waktu tersebut!');
        }

        // Mulai menjalankan query
        DB::beginTransaction();
        try{
            $penyewa = User::where('no_telp', $request->no_hp)->first();

            if (!$penyewa) {
                $penyewa = User::create([
                    'nama_lengkap'  => $request->nama_tamu,
                    'no_telp'       => $request->no_hp,
                    'email'         => $request->no_hp . '@guest.local',
                    'username'      => 'guest_' . $request->no_hp,
                    'password'      => Hash::make('guest'. $request->no_hp),
                    'role'          => 'penyewa',
                ]);
            }
            $idPenyewa = $penyewa->id_penyewa ?? $penyewa->id;

            // Hitung harga Kamar
            $totalHargaKamar = $kamar->harga_kamar * $request->durasi;
            $grandTotal = $totalHargaKamar;

            // Hitung harga Service (jika villa & ada yang dipilih)
            $serviceDataToInsert = [];
            if ($request->has('services')) {
                foreach ($request->services as $serviceId) {
                    $qty = $request->service_qty[$serviceId] ?? 1;
                    if ($qty > 0) {
                        $svc = Service::find($serviceId);
                        if ($svc) {
                            $subtotalService = $svc->price * $qty;
                            $grandTotal += $subtotalService;

                            $serviceDataToInsert[] = [
                                'id_service' => $svc->id,
                                'quantity'   => $qty,
                                'price'      => $svc->price,
                                'subtotal'   => $subtotalService
                            ];
                        }
                    }
                }
            }

            // Input Pemesanan
            $pemesanan = Pemesanan::create([
                'id_pemesanan'      => $this->generateKodePemesanan(),
                'id_penyewa'        => $idPenyewa,
                'id_cabang'         => $idCabang,
                'waktu_pemesanan'   => now(),
                'total_harga'       => $grandTotal,
                'status'            => $request->status_bayar,
                'expired_at'        => $request->status_bayar == 'Belum Dibayar' ? now()->addHours(24) : null,
            ]);

            // Input PemesananItem (Kamar)
            PemesananItem::create([
                'id_pemesanan'   => $pemesanan->id_pemesanan,
                'id_kamar'       => $kamar->id_kamar,
                'jumlah_pesan'   => 1,
                'harga'          => $totalHargaKamar,
                'waktu_checkin'  => $checkin->format('Y-m-d'),
                'waktu_checkout' => $checkout->format('Y-m-d'),
            ]);

            // Input PemesananService
            foreach ($serviceDataToInsert as $svcData) {
                PemesananService::create([
                    'id_pemesanan'  => $pemesanan->id_pemesanan,
                    'id_service'    => $svcData['id_service'],
                    'quantity'      => $svcData['quantity'],
                    'price'         => $svcData['price'],
                    'subtotal'      => $svcData['subtotal'],
                ]);
            }

            // Update status Kamar (Jika Pesan Hari ini & Lunas)
            if($checkin->isToday() && $request->status_bayar == 'Lunas') {
                $kamar->update(['status' => 'Dihuni']);
            }

            DB::commit();
            Alert::success('Berhasil', 'Pesanan berhasil dibuat.');
            return redirect()->route('pengelola.pemesanan.index');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    private function generateKodePemesanan(): string
    {
        $last = Pemesanan::orderBy('id_pemesanan', 'desc')->first();
        if (!$last) return 'PS00001';
        $num = (int) substr($last->id_pemesanan, 2);
        $num++;
        return 'PS' . str_pad($num, 5, '0', STR_PAD_LEFT);
    }

    public function show($id)
    {
        $pengelola = Auth::user();
        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->with(['items.kamar', 'service.service', 'penyewa'])
            ->firstOrFail();

        return view('pengelola.daftar-pemesanan.show', compact('pemesanan'));
    }

    public function destroy($id)
    {
        $pengelola = Auth::user();

        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->with(['items', 'service'])
            ->firstOrFail();

        DB::transaction(function () use ($pemesanan) {
            // Kembalikan status kamar
            if($pemesanan->status == 'Lunas' || $pemesanan->status == 'Belum Dibayar') {
                foreach ($pemesanan->items as $item) {
                    $kamar = Kamar::find($item->id_kamar);
                    if($kamar) {
                        $kamar->update(['status' => 'Tersedia']);
                    }
                }
            }

            // Kembalikan Stok Service
            foreach ($pemesanan->service as $ps) {
                $svc = Service::find($ps->id_service);
                if($svc) $svc->increment('stock', $ps->quantity);
            }

            // Hapus data
            $pemesanan->delete();
        });

        Alert::success('Berhasil', 'Pesanan berhasil dihapus.');
        return redirect()->back();
    }

    public function verifikasi($id)
    {
        $pengelola = Auth::user();

        DB::transaction(function () use ($id, $pengelola) {
            $pemesanan = Pemesanan::where('id_pemesanan', $id)
                ->where('id_cabang', $pengelola->cabang->id_cabang)
                ->with('items')
                ->firstOrFail();

            // 1. Update Status Pemesanan ini menjadi Lunas
            $pemesanan->update([
                'status'     => 'Lunas',
                'expired_at' => null
            ]);

            // 2. Ambil semua ID Kamar dalam pemesanan ini
            $kamarIds = $pemesanan->items->pluck('id_kamar')->toArray();

            // 3. Ubah status kamar menjadi Dihuni
            Kamar::whereIn('id_kamar', $kamarIds)->update(['status' => 'Dihuni']);

            // 4. LOGIKA AUTO-CANCEL:
            // Batalkan semua pemesanan lain yang mengandung kamar yang sama dan masih berstatus 'Belum Dibayar'
            Pemesanan::where('id_pemesanan', '!=', $id)
                ->where('status', 'Belum Dibayar')
                ->whereHas('items', function($query) use ($kamarIds) {
                    $query->whereIn('id_kamar', $kamarIds);
                })
                ->update([
                    'status' => 'Dibatalkan',
                    'cancelled_at' => now(),
                    'alasan_batal' => 'Kamar telah diverifikasi untuk pemesan lain.'
                ]);
        });

        Alert::success('Berhasil', 'Pemesanan berhasil diverifikasi.');
        return redirect()->back();
    }

    public function batalkan(Request $request, $id)
    {
        $request->validate(['alasan_batal' => 'required|string|max:500']);
        $pengelola = Auth::user();

        $pemesanan = Pemesanan::where('id_pemesanan', $id)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->firstOrFail();

        $pemesanan->update([
            'status' => 'Dibatalkan',
            'cancelled_at' => now(),
            'alasan_batal' => $request->alasan_batal
        ]);

        Alert::error('Dibatalkan', 'Pemesanan berhasil dibatalkan.');
        return redirect()->back();
    }

    public function checkout($id_pemesanan)
    {
        $pengelola = Auth::user();

        // Temukan pemesanan
        $pemesanan = Pemesanan::where('id_pemesanan', $id_pemesanan)
            ->where('id_cabang', $pengelola->cabang->id_cabang)
            ->with(['items', 'service'])->firstOrFail();

        // Validasi
        if($pemesanan->status == 'Lunas' && $pemesanan->expired_at != null) {
            return back()->with('error', 'Pesanan ini sudah diselesaikan sebelumnya.');
        }

        DB::transaction(function () use ($pemesanan) {
            // A. KEMBALIKAN STATUS KAMAR
            if ($pemesanan->items->isNotEmpty()) {
                foreach ($pemesanan->items as $item) {
                    $kamar = Kamar::find($item->id_kamar);
                    if ($kamar) {
                        $kamar->update(['status' => 'Tersedia']);
                    }
                }
            }

            // B. KEMBALIKAN STOK SERVICE
            if ($pemesanan->service->isNotEmpty()) {
                foreach ($pemesanan->service as $ps) {
                    // Cari service master
                    $masterService = Service::find($ps->id_service);
                    if ($masterService) {
                        // Kembalikan stok
                        $masterService->increment('stock', $ps->quantity);
                    }
                }
            }

            // C. TANDAI SELESAI
            // Kita isi expired_at dengan waktu sekarang sebagai penanda "Selesai"
            $pemesanan->update([
                'expired_at' => now()
            ]);
        });

        $msg = $pemesanan->items->isNotEmpty()
        ? 'Check-out Berhasil. Kamar kembali tersedia.'
        : 'Layanan Selesai. Stok layanan telah dikembalikan.';

        Alert::success('Selesai', $msg);
        return back();
    }
}
