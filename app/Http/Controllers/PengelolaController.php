<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Pemesanan;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// use Illuminate\Http\Request;

class PengelolaController extends Controller
{
    public function index()
    {
        $pengelola = Auth::guard('pengelola')->user();
        $cabang = $pengelola->cabang;

        return view('pengelola.dashboard', compact('pengelola', 'cabang'));
    }

    public function dashboardData()
    {
        $pengelola = Auth::guard('pengelola')->user();
        $id_cabang = $pengelola->id_cabang;
        $kategori = Str::lower($pengelola->cabang->kategori_cabang);

        // Data Statistik
        $totalKamar = Kamar::where('id_cabang', $id_cabang)->count();

        $totalPenyewa = Pemesanan::where('id_cabang', $id_cabang)
            ->whereIn('status', ['Lunas', 'Checkin', 'Selesai'])
            ->distinct('id_penyewa')
            ->count('id_penyewa');

        $totalPesanan = Pemesanan::where('id_cabang', $id_cabang)->count();

        $pesananBelumDikonfirmasi = Pemesanan::where('id_cabang', $id_cabang)
            ->where('status', 'Belum Dibayar')
            ->count();

        // Data Chart
        $chartPenyewa = Pemesanan::select( //tren penyewa
            DB::raw('MONTH(waktu_pemesanan) as bulan'),
            DB::raw('COUNT(*) as total')
        )
        ->where('id_cabang', $id_cabang)
        ->whereYear('waktu_pemesanan', date('Y'))
        ->where('status', '!=', 'Dibatalkan')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->pluck('total', 'bulan')
        ->toArray();

        $dataBulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulan[] = $chartPenyewa[$i] ?? 0;
        }

        $labelTipe = [];
        $dataTipe = [];
        if ($kategori === 'kost') {
            $chartTipeKamar = Kamar::select('tipe_kamar', DB::raw('count(*) as total'))
                ->where('id_cabang', $id_cabang)
                ->groupBy('tipe_kamar')
                ->get();
            $labelTipe = $chartTipeKamar->pluck('tipe_kamar');
            $dataTipe  = $chartTipeKamar->pluck('total');
        }

        // Bar chart
        $kamarTerpakai = Kamar::where('id_cabang', $id_cabang)
            ->where('status', 'Dihuni')
            ->count();

        $serviceTotalStok = 0;
        $serviceTerpakai  = 0;
        if ($kategori === 'villa') {
            $serviceTotalStok = Service::where('id_cabang', $id_cabang)->sum('stock');

            $services = Service::where('id_cabang', $id_cabang)
                ->withSum(['pemesananService as used' => function($q) {
                    $q->whereHas('pemesanan', function($q) {
                        $q->whereIn('status', ['Lunas', 'Checkin']);
                    });
            }], 'quantity')->get();
            $serviceTerpakai = $services->sum('used');
        }
        $barTotal    = [$totalKamar, $serviceTotalStok];
        $barTerpakai = [$kamarTerpakai, $serviceTerpakai];


        return response()->json([
            'total_kamar'           => $totalKamar,
            'total_penyewa'         => $totalPenyewa,
            'total_pesanan'         => $totalPesanan,
            'total_belum_konfirmasi'=> $pesananBelumDikonfirmasi,
            'chart_line'            => $dataBulan,
            'chart_doughnut_labels' => $labelTipe,
            'chart_doughnut_data'   => $dataTipe,
            'chart_bar_total'       => $barTotal,
            'chart_bar_terpakai'    => $barTerpakai
        ]);
    }

    public function edit()
    {
        $pengelola = Auth::guard('pengelola')->user();
        return view('pengelola.profile', compact('pengelola'));
    }

    public function update(Request $request)
    {
        $pengelola = Auth::guard('pengelola')->user();

        $validasiData = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'username'     => ['required', 'string', 'max:255', 'min:8', Rule::unique('pengelola')->ignore($pengelola->id_pengelola, 'id_pengelola')],
            'email'        => ['required', 'email', Rule::unique('pengelola')->ignore($pengelola->id_pengelola, 'id_pengelola')],
            'no_telp'      => ['nullable', 'numeric'],
            'foto_profil'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ],
        [
                'required' => 'Data ini wajib diisi, jangan dikosongkan!',
                'numeric' => 'Harus Berupa Angka!',
                'username.unique' => 'Username sudah ada yang gunakan!',
                'email.unique' => 'Email sudah ada yang gunakan!',
                'nama_lengkap.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',
                'username.min' => 'Username minimal memiliki panjang 8 karakter',
                'image' => 'Gambar harus berformat: jpg, jpeg, atau png!',
                'foto_profil.max' => 'Ukuran gambar maks: 2mb!'
            ]);

        // Sanitasi Input
        $kolomInput = ['nama_lengkap', 'username', 'email'];
        foreach ($kolomInput as $kolom) {
            if(isset($validasiData[$kolom])) {
                $validasiData[$kolom] = strip_tags($validasiData[$kolom]);
            }
        }

        if ($request->hasFile('foto_profil')) {
            if ($pengelola->foto_profil && Storage::disk('public')->exists($pengelola->foto_profil)) {
                Storage::disk('public')->delete($pengelola->foto_profil);
            }
            $validasiData['foto_profil'] = $request->file('foto_profil')->store('pengelola-photos', 'public');
        }

        $pengelola->update($validasiData);

        return redirect()->route('pengelola.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
