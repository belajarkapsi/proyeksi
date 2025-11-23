@extends('layout.master')
@section('title', $cabang->nama_cabang)

@section('content')

<a href="/dashboard" class="font-medium text-sm text-blue-600 hover:underline">&laquo; Kembali ke Dashboard</a>

@if(strtolower($cabang->kategori_cabang) === 'kost')
<header class="relative w-full py-12 flex justify-center items-center overflow-hidden">
    <h1 class="absolute text-[120px] font-bold text-gray-400 opacity-20 select-none whitespace-nowrap font-serif">
        {{ $cabang->nama_cabang }}
    </h1>
    <h2 class="relative text-4xl md:text-5xl font-extrabold text-gray-900 font-serif">
        {{ $cabang->nama_cabang }}
    </h2>
</header>


<section class="bg-white py-10">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 px-6">

        {{-- BAGIAN GAMBAR --}}
        <div class="relative">
            {{-- Background hijau seperti desain --}}
            <div class="absolute -top-6 -left-6 w-72 h-72 bg-green-500 rounded-xl -z-10"></div>

            @if ($cabang->nama_cabang === 'Pondok Satu')
            <img class="rounded-lg shadow-lg w-full max-h-[420px] object-cover"
                src="{{ asset('images/pondok.jpg') }}"
                alt="Foto cabang" />
            @elseif ($cabang->nama_cabang === 'Pondok Siti Hajar')
            <img class="rounded-lg shadow-lg w-full max-h-[420px] object-cover"
                src="{{ asset('images/background.jpg') }}"
                alt="Foto cabang" />
            @else
            <img class="rounded-lg shadow-lg w-full max-h-[420px] object-cover"
                src="{{ asset('images/background.jpg') }}"
                alt="Foto cabang" />
            @endif
        </div>

        {{-- BAGIAN DESKRIPSI DAN TEMPAT TERDEKAT --}}
        <div>
            {{-- Cerita Kost --}}
            <h2 class="text-3xl font-extrabold text-green-600 mb-4">Cerita Tentang Kost Ini</h2>
            <p class="text-gray-600 leading-relaxed mb-8">
                {{ $cabang->deskripsi }}
            </p>

            {{-- Tempat Terdekat --}}
            <h3 class="text-xl font-bold text-gray-800 mb-4">Tempat terdekat:</h3>

            <ul class="space-y-3 text-gray-700">
                <li class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z"></path><path d="M7 16v6"></path><path d="M13 19v3"></path><path d="M12 19h8.3a1 1 0 0 0 .7-1.7L18 14h.3a1 1 0 0 0 .7-1.7L16 9h.2a1 1 0 0 0 .8-1.7L13 3l-1.4 1.5"></path></svg>
                    <div>
                        <p class="font-medium">Taman Mattirotasi</p>
                        <p class="text-sm text-gray-500">800 m</p>
                    </div>
                </li>

                <li class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"></path><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"></path><path d="M18 5v17"></path><path d="m4 6 8-4 8 4"></path><path d="M6 5v17"></path><circle cx="12" cy="9" r="2"></circle></svg>
                    <div>
                        <p class="font-medium">Institut Teknologi B.J. Habibie</p>
                        <p class="text-sm text-gray-500">1 km</p>
                    </div>
                </li>

                <li class="flex items-center gap-3">
                    <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="2" d="M7.42584 18.2443c1.3169-2.2023 2.5334-1.5242 3.85026-3.7265 1.3169-2.2023.1004-2.8805 1.4173-5.08281 1.3169-2.20233 2.5335-1.52419 3.8504-3.72652M10.8472 20.1517c1.3169-2.2024 2.5334-1.5242 3.8503-3.7266 1.3169-2.2023.1004-2.8804 1.4173-5.0828 1.3169-2.20228 2.5334-1.52414 3.8503-3.72647l-6.8428-3.81455C11.8054 6.00361 10.5889 5.32547 9.272 7.5278s-.1004 2.8805-1.4173 5.0828c-1.3169 2.2023-2.5334 1.5242-3.85031 3.7265l6.84281 3.8146Z"/></svg>
                    <div>
                        <p class="font-medium">Mie Gacoan</p>
                        <p class="text-sm text-gray-500">1.2 km</p>
                    </div>
                </li>

                <li class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"></path><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"></path><path d="M12 10v4"></path><path d="M12 2v3"></path></svg>
                    <div>
                        <p class="font-medium">Pelabuhan Nusantara</p>
                        <p class="text-sm text-gray-500">1.5 km</p>
                    </div>
                </li>

                <li class="flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6v4"></path><path d="M14 14h-4"></path><path d="M14 18h-4"></path><path d="M14 8h-4"></path><path d="M18 12h2a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2"></path><path d="M18 22V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v18"></path></svg>
                    <div>
                        <p class="font-medium">RSUD Andi Makkasau</p>
                        <p class="text-sm text-gray-500">2.3 km</p>
                    </div>
                </li>
            </ul>

            <div class="mt-auto ml-auto flex flex-col items-center pt-4 w-fit">
                    @php
                        // Hitung total kamar dari semua tipe yang ada di cabang ini
                        // Asumsi: $types adalah collection grouped by tipe_kamar, jadi kita ratakan dulu
                        $totalKamarTersedia = $cabang->kamars()->where('status', 'Tersedia')->count();
                    @endphp

                    <p class="text-gray-600 mb-2 text-sm font-medium text-center">
                        Tersisa <span class="text-green-600 font-bold text-lg">{{ $totalKamarTersedia }} Kamar</span>
                    </p>

                    {{-- Tombol Lihat Semua Tipe --}}
                    {{-- Route ini mengarah ke CabangController@type (menampilkan list semua kamar) --}}
                    <a href="/cabang/parepare/kost/kamar"
                       class="px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-full shadow-md transition-transform transform hover:scale-105">
                        Lihat Semua Kamar
                    </a>
                </div>
        </div>

    </div>
</section>


@elseif(strtolower($cabang->kategori_cabang) === 'villa')
<div class="cabang-detail">
        <h1>{{ $cabang->nama_cabang }}</h1>
        <p><strong>Lokasi:</strong> {{ $cabang->lokasi }}</p>
        <p><strong>Kategori:</strong> {{ ucfirst($cabang->kategori_cabang) }}</p>
        <p><strong>Jumlah Kamar:</strong> {{ $cabang->jumlah_kamar }}</p>
        <p>{{ $cabang->deskripsi }}</p>

        @if($cabang->gambar_cabang)
            <div class="gambar">
                <img src="{{ asset('storage/cabang/' . $cabang->gambar_cabang) }}" alt="{{ $cabang->nama_cabang }}" style="max-width:100%;height:auto;">
            </div>
        @endif
</div>
@endif

@endsection
