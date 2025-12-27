<?php

namespace App\Http\Controllers\Pengelola;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; // Pastikan import ini ada
use RealRashid\SweetAlert\Facades\Alert;

class DataServiceController extends Controller
{
    private function cabang()
    {
        return auth('pengelola')->user()->cabang;
    }

    public function create()
    {
        // Cek policy 'create' (otomatis cek apakah dia Villa atau bukan)
        Gate::authorize('create', Service::class);

        return view('pengelola.data-service.create');
    }

    public function store(Request $request)
    {
        // Cek policy lagi sebelum simpan
        Gate::authorize('create', Service::class);

        $validasi = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
        ]);

        $validasi['id_cabang'] = $this->cabang()->id_cabang;

        Service::create($validasi);

        Alert::success('Berhasil!', 'Layanan Villa berhasil ditambahkan.');
        return redirect()->route('pengelola.kamar.index');
    }

    public function edit(Service $service)
    {
        // Cek policy 'update' (otomatis cek kepemilikan & tipe villa)
        Gate::authorize('update', $service);

        return view('pengelola.data-service.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        Gate::authorize('update', $service);

        $validasi = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'nullable|integer|min:0',
        ]);

        $service->update($validasi);

        Alert::success('Berhasil!', 'Layanan Villa berhasil diperbarui.');
        return redirect()->route('pengelola.kamar.index');
    }

    public function destroy(Service $service)
    {
        Gate::authorize('delete', $service);

        $service->delete();

        Alert::success('Berhasil!', 'Layanan Villa berhasil dihapus.');
        return redirect()->route('pengelola.kamar.index');
    }
}
