@extends('layout.admin')
@section('title', 'Informasi Cabang')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-400 hover:text-green-600">
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Informasi Cabang {{ ucfirst($lokasi) }}</span>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto">
    <div class="flex justify-between items-center py-4 mb-4">
        <h2 class="text-2xl font-semibold text-gray-700">
            Daftar Cabang {{ ucfirst($lokasi) }}
        </h2>
        {{-- Tombol Tambah (Opsional, kalau mau nambah cabang baru) --}}
        {{-- <a href="{{ route('admin.cabang.informasi-cabang.create', $lokasi) }}" class="...">+ Tambah Cabang</a> --}}
    </div>

    {{-- GRID CARD --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($cabangs as $cabang)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden flex flex-col h-full">

            {{-- GAMBAR CABANG (Cover) --}}
            <div class="relative h-48 w-full bg-gray-200">
                @if($cabang->gambar_cabang && $cabang->gambar_cabang != 'dummy.jpg')
                    <img src="{{ asset('storage/' . $cabang->gambar_cabang) }}" alt="{{ $cabang->nama_cabang }}" class="w-full h-full object-cover">
                @else
                    {{-- Placeholder jika tidak ada gambar --}}
                    <div class="flex items-center justify-center h-full text-gray-400 flex-col">
                        <i class="fa-regular fa-image text-4xl mb-2"></i>
                        <span class="text-sm">Tidak ada gambar</span>
                    </div>
                @endif

                {{-- BADGE KATEGORI (Pojok Kanan Atas) --}}
                <div class="absolute top-3 right-3">
                    @php
                        $isVilla = strtolower($cabang->kategori_cabang) == 'villa';
                        $badgeClass = $isVilla ? 'bg-purple-600' : 'bg-blue-600';
                    @endphp
                    <span class="{{ $badgeClass }} text-white text-xs font-bold px-3 py-1 rounded-full shadow uppercase tracking-wide">
                        {{ $cabang->kategori_cabang }}
                    </span>
                </div>
            </div>

            {{-- KONTEN CARD --}}
            <div class="p-5 flex-1 flex flex-col">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900">
                    {{ $cabang->nama_cabang }}
                </h5>

                <div class="flex items-center text-sm text-gray-500 mb-4 gap-4">
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-green-600"></i> {{ $cabang->lokasi }}
                    </span>
                    <span class="flex items-center gap-1">
                        <i class="fa-solid fa-door-open text-green-600"></i> {{ $cabang->jumlah_kamar }} Kamar
                    </span>
                </div>

                <p class="mb-4 font-normal text-gray-600 text-sm line-clamp-3">
                    {{ $cabang->deskripsi }}
                </p>

                {{-- FOOTER CARD (Tombol Aksi) --}}
                <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center">
                    <span class="text-xs text-gray-400">Diupdate: {{ $cabang->updated_at->diffForHumans() }}</span>

                    <div class="flex gap-2">
                        {{-- Tombol Edit --}}
                        <a href="{{ route('admin.cabanginformasi-cabang.edit', ['lokasi' => $lokasi, 'informasi_cabang' => $cabang->id_cabang]) }}"
                        class="text-white bg-amber-500 hover:bg-amber-600 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center gap-2 transition-colors">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>

                        {{-- Tombol Hapus (Opsional, pakai sweetalert) --}}
                        {{--
                        <button type="button" onclick="confirmDelete('{{ $cabang->id_cabang }}', '{{ $cabang->nama_cabang }}')" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        --}}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-10 text-gray-500">
            <p>Data cabang belum tersedia untuk lokasi ini.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
