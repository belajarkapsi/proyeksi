@extends('layout.pengelola')
@section('title', 'Data Kamar & Layanan')

@section('content')
{{-- Navbar Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-4">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Data Kamar & Layanan</span>
            </li>
        </ol>
    </nav>
</div>

{{-- Main Content --}}
<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto pb-10">

    {{-- Header & Tombol Tambah Kamar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-5 mb-6 gap-4">
        <div>
            <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-green-600 text-sm transition-colors mb-2">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Data Kamar</h2>
            <p class="text-sm text-gray-500">Kelola ketersediaan kamar dan informasi hunian.</p>
        </div>

        @can('create', App\Models\Kamar::class)
        <a href="{{ route('pengelola.kamar.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm font-semibold text-sm">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Kamar
        </a>
        @endcan
    </div>

    {{-- Pisahkan data berdasarkan status --}}
    @php
        $kamarTersedia = $kamars->where('status', 'Tersedia');
        $kamarDihuni = $kamars->where('status', 'Dihuni');
    @endphp

    {{-- SECTION 1: KAMAR TERSEDIA --}}
    <div class="mb-10">
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-green-100 text-green-700 p-2 rounded-lg">
                <i class="fas fa-door-open"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Kamar Tersedia <span class="text-sm font-normal text-gray-500">({{ $kamarTersedia->count() }})</span></h3>
        </div>

        @if($kamarTersedia->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($kamarTersedia as $kamar)
                    <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden">
                        {{-- Gambar --}}
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            @if($kamar->gambar)
                                <img src="{{ asset('storage/' . $kamar->gambar) }}" alt="Kamar" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <span class="text-xs">Tidak ada gambar</span>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded border border-green-200 shadow-sm">
                                    Tersedia
                                </span>
                            </div>
                        </div>

                        {{-- Konten --}}
                        <div class="p-4 flex flex-col grow">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-bold text-gray-900">No. {{ $kamar->no_kamar }}</h4>
                                <span class="text-xs font-medium px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $kamar->tipe_kamar }}
                                </span>
                            </div>
                            <p class="text-green-600 font-bold text-lg mb-3">
                                Rp {{ number_format($kamar->harga_kamar, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/malam</span>
                            </p>
                            <p class="text-sm text-gray-500 line-clamp-2 mb-4 grow">
                                {{ $kamar->deskripsi }}
                            </p>

                            {{-- Actions Buttons --}}
                            <div class="flex items-center gap-2 mt-auto pt-3 border-t border-gray-100">
                                @can('view', $kamar)
                                <a href="{{ route('pengelola.kamar.show', $kamar->id_kamar) }}" class="flex-1 inline-flex justify-center items-center py-2 px-3 text-sm font-medium text-gray-700 bg-white rounded border border-gray-300 hover:bg-gray-50 hover:text-green-600 transition" title="Lihat Detail">
                                    <i class="far fa-eye"></i>
                                </a>
                                @endcan
                                @can('update', $kamar)
                                <a href="{{ route('pengelola.kamar.edit', $kamar->id_kamar) }}" class="flex-1 inline-flex justify-center items-center py-2 px-3 text-sm font-medium text-amber-600 bg-amber-50 rounded border border-amber-200 hover:bg-amber-100 transition" title="Edit">
                                    <i class="far fa-edit"></i>
                                </a>
                                @endcan
                                @can('delete', $kamar)
                                <form id="delete-form-{{ $kamar->id_kamar }}" action="{{ route('pengelola.kamar.destroy', $kamar->id_kamar) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $kamar->id_kamar }}', '{{ $kamar->no_kamar }}')" class="w-full inline-flex justify-center items-center py-2 px-3 text-sm font-medium text-red-600 bg-red-50 rounded border border-red-200 hover:bg-red-100 transition" title="Hapus">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
                <i class="fas fa-search fa-2x mb-2 opacity-30"></i>
                <p>Tidak ada kamar yang tersedia saat ini.</p>
            </div>
        @endif
    </div>

    {{-- SECTION 2: KAMAR DIHUNI --}}
    <div>
        <div class="flex items-center gap-2 mb-4">
            <div class="bg-red-100 text-red-700 p-2 rounded-lg">
                <i class="fas fa-user-lock"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Kamar Dihuni <span class="text-sm font-normal text-gray-500">({{ $kamarDihuni->count() }})</span></h3>
        </div>

        @if($kamarDihuni->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($kamarDihuni as $kamar)
                    <div class="group bg-white rounded-xl border border-red-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden ring-1 ring-red-100">
                        {{-- Gambar --}}
                        <div class="relative h-48 bg-gray-100 overflow-hidden">
                            <div class="absolute inset-0 bg-black/40 z-10 flex items-center justify-center">
                                <span class="text-white font-bold border-2 border-white px-3 py-1 rounded uppercase tracking-wider">Terisi</span>
                            </div>
                            @if($kamar->gambar)
                                <img src="{{ asset('storage/' . $kamar->gambar) }}" alt="Kamar" class="w-full h-full object-cover grayscale opacity-70">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 z-20">
                                <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded border border-red-200 shadow-sm">
                                    Dihuni
                                </span>
                            </div>
                        </div>

                        {{-- Konten --}}
                        <div class="p-4 flex flex-col grow bg-red-50/30">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="text-lg font-bold text-gray-900">No. {{ $kamar->no_kamar }}</h4>
                                <span class="text-xs font-medium px-2 py-0.5 rounded bg-white text-gray-600 border border-gray-200 shadow-sm">
                                    {{ $kamar->tipe_kamar }}
                                </span>
                            </div>

                            {{-- Info Penyewa --}}
                            <div class="bg-white p-3 rounded-lg border border-gray-200 mb-3 shadow-sm">
                                <p class="text-xs text-gray-400 uppercase font-bold mb-1">Penghuni Saat Ini:</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 text-xs font-bold">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800 truncate">
                                        {{ $kamar->nama_penyewa ?? 'Data tidak tersedia' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Actions Buttons --}}
                            <div class="flex items-center gap-2 mt-auto pt-3 border-t border-gray-200">
                                <a href="#" class="flex-1 inline-flex justify-center items-center py-2 px-3 text-sm font-medium text-gray-700 bg-white rounded border border-gray-300 hover:bg-gray-50 hover:text-green-600 transition">
                                    <i class="far fa-eye"></i>
                                </a>
                                <a href="{{ route('pengelola.kamar.edit', $kamar->id_kamar) }}" class="flex-1 inline-flex justify-center items-center py-2 px-3 text-sm font-medium text-amber-600 bg-white rounded border border-amber-200 hover:bg-amber-50 transition">
                                    <i class="far fa-edit"></i>
                                </a>
                                {{-- Tombol Hapus Disabled untuk Kamar Dihuni (Opsional) --}}
                                <button type="button" class="flex-1 inline-flex justify-center items-center py-2 px-3 text-sm font-medium text-gray-400 bg-gray-100 rounded border border-gray-200 cursor-not-allowed" title="Tidak bisa menghapus kamar yang sedang dihuni">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-50 rounded-xl border border-dashed border-gray-300 p-8 text-center text-gray-500">
                <i class="fas fa-check-circle fa-2x mb-2 opacity-30 text-green-500"></i>
                <p>Tidak ada kamar yang sedang dihuni.</p>
            </div>
        @endif
    </div>

    {{-- ========================================================= --}}
    {{-- BAGIAN DATA SERVICE / LAYANAN VILLA (HANYA UNTUK VILLA) --}}
    {{-- ========================================================= --}}

    @if(isset($services) && Str::lower(auth('pengelola')->user()->cabang->kategori_cabang) === 'villa')

        {{-- PEMISAH --}}
        <div class="my-12 border-t-4 border-dashed border-gray-200 relative">
            <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-gray-50 px-4 py-1 rounded-full text-xs font-bold text-gray-400 uppercase tracking-widest border border-gray-200">
                Fasilitas Tambahan
            </span>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Layanan Villa</h2>
                <p class="text-sm text-gray-500">Kelola item tambahan seperti Extra Bed, Gazebo, dll.</p>
            </div>

            @can('create', App\Models\Service::class)
            <a href="{{ route('pengelola.service.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold text-sm">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Layanan
            </a>
            @endcan
        </div>

        {{-- GRID SERVICE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($services as $service)
                @php
                    $terpakai = $service->sedang_dipakai ?? 0;
                    $sisa = $service->stock - $terpakai;
                    // Tentukan warna border berdasarkan status
                    $borderColor = ($terpakai > 0) ? 'border-red-300 ring-2 ring-red-50' : 'border-gray-200';
                @endphp

                <div class="bg-white rounded-xl border {{ $borderColor }} shadow-sm hover:shadow-md transition-all duration-300 p-5 flex flex-col relative overflow-hidden">

                    {{-- Badge Status (Habis/Tersedia) --}}
                    <div class="absolute top-0 right-0 p-3">
                        @if($sisa <= 0)
                            <span class="px-2 py-1 bg-gray-200 text-gray-600 text-xs font-bold rounded">Habis</span>
                        @elseif($sisa <= 2)
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs font-bold rounded">Sisa {{ $sisa }}</span>
                        @else
                            <span class="px-2 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded">Stok {{ $sisa }}</span>
                        @endif
                    </div>

                    <div class="flex items-start gap-4 mb-4">
                        {{-- Icon --}}
                        <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                            <i class="fa-solid fa-box-open text-xl"></i>
                        </div>

                        <div class="pr-8">
                            <h4 class="text-lg font-bold text-gray-800 leading-tight mb-1">{{ $service->name }}</h4>
                            <p class="text-green-600 font-bold text-sm">Rp {{ number_format($service->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2 min-h-10">
                        {{ $service->description ?? 'Tidak ada deskripsi.' }}
                    </p>

                    {{-- Info Penggunaan (Bagian Penting) --}}
                    <div class="bg-gray-50 rounded-lg p-3 mb-4 border border-gray-100">
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="text-gray-500">Total Stok</span>
                            <span class="font-bold text-gray-800">{{ $service->stock }} Unit</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500">Sedang Dipakai</span>
                            @if($terpakai > 0)
                                <span class="font-bold text-red-600 flex items-center gap-1.5">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                    </span>
                                    {{ $terpakai }}x Dipesan
                                </span>
                            @else
                                <span class="text-gray-400 font-medium">-</span>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 mt-auto">
                        @can('update', $service)
                        <a href="{{ route('pengelola.service.edit', $service->id) }}" class="flex-1 py-2 text-center text-sm font-bold text-blue-600 bg-blue-50 rounded hover:bg-blue-100 transition">
                            Edit
                        </a>
                        @endcan

                        @can('delete', $service)
                        <form id="delete-service-{{ $service->id }}" action="{{ route('pengelola.service.destroy', $service->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeleteService('{{ $service->id }}', '{{ $service->name }}')" class="w-full py-2 text-center text-sm font-bold text-red-600 bg-red-50 rounded hover:bg-red-100 transition">
                                Hapus
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="col-span-full py-8 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300 text-gray-500">
                    <p>Belum ada layanan tambahan.</p>
                </div>
            @endforelse
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Hapus Kamar
    function confirmDelete(id, noKamar) {
        Swal.fire({
            title: 'Hapus Kamar ' + noKamar + '?',
            text: "Data kamar ini akan dihapus permanen! Hati-hati jika kamar sedang dihuni.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    // Konfirmasi Hapus Service
    function confirmDeleteService(id, name) {
        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Layanan \"" + name + "\" akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-service-' + id).submit();
            }
        })
    }
</script>
@endpush
