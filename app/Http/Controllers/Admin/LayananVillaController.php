<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Service;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LayananVillaController extends Controller
{
    // Helper: Cari ID Cabang khusus kategori VILLA di lokasi tersebut
    private function getVillaCabang($lokasi)
    {
        // Cari cabang berdasarkan lokasi DAN kategori Villa
        $cabang = Cabang::where('lokasi', $lokasi)
                        ->where('kategori_cabang', 'villa') // CONSTRAINT VILLA
                        ->first();

        if (!$cabang) {
            abort(404, "Cabang kategori Villa tidak ditemukan di lokasi $lokasi");
        }

        return $cabang;
    }

    public function index($lokasi)
    {
        $cabang = $this->getVillaCabang($lokasi);

        // Ambil service milik cabang ini saja
        $services = Service::where('id_cabang', $cabang->id_cabang)
                    ->latest()
                    ->paginate(10);

        return view('admin.layanan-villa.index', compact('cabang', 'lokasi', 'services'));
    }

    public function create($lokasi)
    {
        // Cek validitas cabang villa
        $this->getVillaCabang($lokasi);
        return view('admin.layanan-villa.create', compact('lokasi'));
    }

    public function store(Request $request, $lokasi)
    {
        $cabang = $this->getVillaCabang($lokasi);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['id_cabang'] = $cabang->id_cabang;

        Service::create($validated);

        Alert::success('Berhasil!', 'Layanan Villa berhasil ditambahkan');
        return redirect()->route('admin.cabanglayanan-villa.index', $lokasi);
    }

    public function edit($lokasi, $id)
    {
        $this->getVillaCabang($lokasi);
        $service = Service::findOrFail($id);

        return view('admin.layanan-villa.edit', compact('lokasi', 'service'));
    }

    public function update(Request $request, $lokasi, $id)
    {
        $service = Service::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $service->update($validated);

        Alert::success('Berhasil!', 'Informasi layanan berhasil diperbarui');
        return redirect()->route('admin.cabanglayanan-villa.index', $lokasi);
    }

    public function destroy($lokasi, $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        Alert::success('Berhasil!', 'Layanan Villa berhasil dihapus');
        return redirect()->route('admin.cabanglayanan-villa.index', $lokasi);
    }
}
