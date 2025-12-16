<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Kamar;
use App\Models\Cabang;
use App\Models\Service;
use App\Models\PemesananService;
use App\Models\PemesananItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DaftarPemesananController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status');

        $query = Pemesanan::with([
            'penyewa',
            'cabang',
            'items.kamar',
            'service.service',
        ])->latest();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('id_pemesanan', 'like', "%{$q}%")
                    ->orWhereHas('penyewa', function ($q2) use ($q) {
                        $q2->where('username', 'like', "%{$q}%")
                           ->orWhere('nama_lengkap', 'like', "%{$q}%");
                    });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $pemesanan = $query->paginate(15)->withQueryString();

        return view('admin.daftar-pemesanan.index', compact('pemesanan', 'q', 'status'));
    }

    public function create()
    {
        return view('admin.daftar-pemesanan.form', [
            'pemesanan' => new Pemesanan(),
            'cabangs'   => Cabang::all(),
            'kamars'    => Kamar::all(),
            'services'  => Service::all(),
            'penyewas'  => User::all(),
            'method'    => 'post',
            'action'    => route('daftar-pemesanan.store'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_penyewa'      => 'required|exists:penyewa,id_penyewa',
            'id_cabang'       => 'required|exists:cabang,id_cabang',
            'waktu_pemesanan' => 'required|date',
            'total_harga'     => 'required|numeric',
            'status'          => 'required|in:Belum Dibayar,Lunas,Dibatalkan',
        ]);

        if (empty($request->id_pemesanan)) {
            $last = Pemesanan::orderBy('id_pemesanan', 'desc')->first();
            $num  = $last ? ((int) substr($last->id_pemesanan, 2)) + 1 : 1;
            $data['id_pemesanan'] = 'PS' . str_pad($num, 5, '0', STR_PAD_LEFT);
        } else {
            $data['id_pemesanan'] = $request->id_pemesanan;
        }

        DB::transaction(function () use ($request, $data) {
            $pemesanan = Pemesanan::create($data);

            // === ITEMS KAMAR ===
            if ($request->filled('items')) {
                foreach ($request->items as $item) {
                    if (empty($item['no_kamar'])) continue;

                    $kamar = Kamar::where('no_kamar', $item['no_kamar'])->first();
                    if (!$kamar) continue;

                    PemesananItem::create([
                        'id_pemesanan'   => $pemesanan->id_pemesanan,
                        'id_kamar'       => $kamar->id_kamar,
                        'jumlah_pesan'   => $item['jumlah_pesan'] ?? 1,
                        'harga'          => $kamar->harga_kamar,
                        'waktu_checkin'  => $item['waktu_checkin'],
                        'waktu_checkout' => $item['waktu_checkout'],
                    ]);
                }
            }

            // === SERVICES ===
            if ($request->filled('services')) {
                foreach ($request->services as $serviceId) {
                    $service = Service::find($serviceId);
                    if (!$service) continue;

                    PemesananService::create([
                        'id_pemesanan' => $pemesanan->id_pemesanan,
                        'id_service'   => $service->id,
                        'qty'          => 1,
                        'price'        => $service->price,
                    ]);
                }
            }
        });

        Alert::success('Berhasil', 'Pemesanan berhasil dibuat');
        return redirect()->route('daftar-pemesanan.index');
    }

    public function show(Pemesanan $daftar_pemesanan)
    {
        $pemesanan = $daftar_pemesanan->load([
            'penyewa',
            'cabang',
            'items.kamar',
            'service.service',
        ]);

        return view('admin.daftar-pemesanan.show', compact('pemesanan'));
    }

    public function verifikasi(Pemesanan $daftar_pemesanan)
{
    // Guard: hanya boleh diverifikasi jika belum dibayar
    if ($daftar_pemesanan->status !== 'Belum Dibayar') {
        Alert::warning('Info', 'Pemesanan sudah diverifikasi');
        return redirect('/admin/daftar-pemesanan');
    }

    DB::transaction(function () use ($daftar_pemesanan) {

        // 1️⃣ Update status pemesanan
        $daftar_pemesanan->update([
            'status' => 'Lunas'
        ]);

        // 2️⃣ Ambil semua item kamar yang dipesan
        $daftar_pemesanan->load('items.kamar');

        // 3️⃣ Update status kamar → dihuni
        foreach ($daftar_pemesanan->items as $item) {
            if ($item->kamar && $item->kamar->status !== 'dihuni') {
                $item->kamar->update([
                    'status' => 'dihuni'
                ]);
            }
        }
    });

    Alert::success('Berhasil', 'Pemesanan berhasil diverifikasi');
    return redirect('/admin/daftar-pemesanan');
}


    public function destroy(Pemesanan $daftar_pemesanan)
    {
        $daftar_pemesanan->delete();
        Alert::success('Berhasil', 'Pemesanan berhasil dihapus');
        return redirect('/admin/daftar-pemesanan');
    }

    protected function validateData(Request $request, $id = null)
{
    return $request->validate([
        'id_penyewa' => ['required', 'exists:penyewa,id_penyewa'],
        'id_pemesanan' => ['nullable', 'string', 'max:191'],
        'username' => ['nullable', 'string', 'max:191'],
        'no_telp' => ['nullable', 'string', 'max:30'],
        'id_cabang' => ['required', 'exists:cabang,id_cabang'],
        'total_harga' => ['required', 'numeric'],
        'status' => ['required', 'in:Belum Dibayar,Lunas,Dibatalkan'],
        'waktu_pemesanan' => ['nullable', 'date'],
        'items' => ['sometimes', 'array'],
        'items.*.no_kamar' => ['required_with:items', 'exists:kamar,no_kamar'],
        'items.*.harga' => ['nullable', 'numeric'],
        'items.*.jumlah_pesan' => ['nullable', 'integer'],
        'services' => ['sometimes', 'array'],
        'services.*' => ['exists:services,name'],
    ]);
}

}
