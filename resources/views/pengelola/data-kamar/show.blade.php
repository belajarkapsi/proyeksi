@extends('layout.pengelola')

@section('title', 'Detail Kamar ' . $kamar->no_kamar)

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-4 mb-6 ml-3 lg:ml-0">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <a href="{{ route('pengelola.kamar.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">Data Layanan</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Detail Kamar</span>
            </li>
        </ol>
    </nav>
</div>

<div class="container mx-auto px-3 sm:px-2 md:px-4 lg:px-0 xl:px-0 py-3 lg:py-0">
    {{-- Header --}}
    <div class="mb-3">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                Kamar No. {{ $kamar->no_kamar }}
                <span class="px-3 py-1 text-sm rounded-full border {{ $kamar->status == 'Tersedia' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200' }}">
                    {{ $kamar->status }}
                </span>
            </h1>
            <a href="{{ route('pengelola.kamar.index') }}" class="text-gray-500 hover:text-gray-700 text-sm flex items-center gap-1">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI: Informasi Fisik Kamar --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card Foto & Harga --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="relative h-64 sm:h-80 bg-gray-100">
                    @if($kamar->gambar)
                        <img src="{{ asset('storage/' . $kamar->gambar) }}" alt="Foto Kamar" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="fas fa-image fa-4x mb-2 opacity-50"></i>
                            <span class="text-sm">Tidak ada gambar</span>
                        </div>
                    @endif
                    <div class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-4 py-2 rounded-lg shadow-lg">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Harga per Malam</p>
                        <p class="text-xl font-bold text-green-600">Rp {{ number_format($kamar->harga_kamar, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-gray-800">Spesifikasi Kamar</h2>
                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-md text-sm font-medium border border-gray-200">
                            Tipe: {{ $kamar->tipe_kamar }}
                        </span>
                    </div>

                    <div class="prose prose-sm max-w-none text-gray-600">
                        <h3 class="text-sm font-bold text-gray-900 uppercase mb-2">Deskripsi & Fasilitas</h3>
                        <p class="whitespace-pre-line">{{ $kamar->deskripsi }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('pengelola.kamar.edit', $kamar->id_kamar) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition">
                        <i class="far fa-edit mr-2"></i> Edit Data
                    </a>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Status Hunian & Penyewa --}}
        <div class="space-y-6">

            {{-- Card Status Penyewa --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-user-clock text-gray-400"></i> Status Hunian
                    </h3>
                </div>

                <div class="p-6">
                    @if($kamar->status == 'Dihuni' && $penyewa)
                        {{-- TAMPILAN JIKA ADA PENYEWA --}}
                        <div class="text-center mb-6">
                            <div class="relative inline-block">
                                @if($penyewa->foto_profil)
                                    <img src="{{ asset('storage/' . $penyewa->foto_profil) }}" class="w-20 h-20 rounded-full object-cover border-4 border-green-100 shadow-sm mx-auto mb-3">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($penyewa->nama_lengkap ?? $penyewa->username) }}&background=random" class="w-20 h-20 rounded-full object-cover border-4 border-green-100 shadow-sm mx-auto mb-3">
                                @endif
                                <span class="absolute bottom-2 right-0 w-5 h-5 bg-green-500 border-2 border-white rounded-full" title="Sedang Menghuni"></span>
                            </div>

                            <h4 class="text-lg font-bold text-gray-900">{{ $penyewa->nama_lengkap ?? $penyewa->username }}</h4>
                            <p class="text-sm text-gray-500">{{ $penyewa->no_telp ?? '-' }}</p>
                            <a href="mailto:{{ $penyewa->email }}" class="text-xs text-green-600 hover:underline">{{ $penyewa->email ?? '' }}</a>
                        </div>

                        <div class="space-y-3 border-t border-gray-100 pt-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Durasi Sewa</span>
                                <span class="font-medium text-gray-800 text-right">{{ $durasi ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">ID Pesanan</span>
                                <a href="{{ route('pengelola.pemesanan.show', $pemesanan->id_pemesanan) }}" class="font-medium text-blue-600 hover:underline">
                                    #{{ $pemesanan->id_pemesanan }}
                                </a>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="{{ route('pengelola.pemesanan.show', $pemesanan->id_pemesanan) }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition">
                                Lihat Detail Pesanan
                            </a>
                        </div>
                    @else
                        {{-- TAMPILAN JIKA KOSONG --}}
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-gray-400">
                                <i class="fas fa-door-open fa-2x"></i>
                            </div>
                            <h4 class="text-gray-900 font-medium">Kamar Kosong</h4>
                            <p class="text-sm text-gray-500 mt-1">Belum ada penyewa yang menghuni kamar ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Quick Stats (Opsional) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Tambahan</h3>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt mt-1 text-gray-400 w-4"></i>
                        <span>Lokasi: {{ $kamar->cabang->nama_cabang ?? 'Cabang Utama' }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-calendar-alt mt-1 text-gray-400 w-4"></i>
                        <span>Dibuat: {{ $kamar->created_at->format('d M Y') }}</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</div>
@endsection
