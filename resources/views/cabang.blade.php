@extends('layout.master')
@section('title', $cabang->nama_cabang)

@php
    $lokasi = str_replace(' ', '-', strtolower($cabang->lokasi));
    $kategori = str_replace(' ', '-', strtolower($cabang->kategori_cabang));

    // --- LOGIKA DATA LANDMARK BERDASARKAN LOKASI ---
    // Ini membuat tampilan Kost Parepare dan Pangkep memiliki Landmark berbeda
    $landmarks = [];
    
    // Cek jika lokasi mengandung kata 'pare' (Parepare)
    if (str_contains(strtolower($cabang->lokasi), 'pare')) {
        $landmarks = [
            ['name' => 'Taman Mattirotasi', 'dist' => '800 m', 'icon' => 'park'],
            ['name' => 'ITBJ Habibie', 'dist' => '1 km', 'icon' => 'school'],
            ['name' => 'Mie Gacoan', 'dist' => '1.2 km', 'icon' => 'food'],
            ['name' => 'Pelabuhan', 'dist' => '1.5 km', 'icon' => 'ship'],
        ];
    } 
    // Cek jika lokasi mengandung kata 'pangkep' (Pangkep)
    elseif (str_contains(strtolower($cabang->lokasi), 'pangkep')) {
        $landmarks = [
            ['name' => 'Tugu Bambu Runcing', 'dist' => '500 m', 'icon' => 'park'],
            ['name' => 'Taman Musafir', 'dist' => '1 km', 'icon' => 'park'],
            ['name' => 'Politeknik Pertanian', 'dist' => '2 km', 'icon' => 'school'],
            ['name' => 'Terminal Baru', 'dist' => '1.5 km', 'icon' => 'ship'], // Icon generic
        ];
    } 
    // Default jika lokasi lain
    else {
        $landmarks = [
            ['name' => 'Pusat Kota', 'dist' => '500 m', 'icon' => 'park'],
            ['name' => 'Minimarket', 'dist' => '100 m', 'icon' => 'food'],
            ['name' => 'ATM Center', 'dist' => '200 m', 'icon' => 'school'],
            ['name' => 'Jalan Raya', 'dist' => '50 m', 'icon' => 'ship'],
        ];
    }
@endphp

@section('content')
    <!-- GLOBAL FONT LOAD (Berlaku untuk Kost & Villa) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <style>
        .font-roboto { font-family: 'Roboto', sans-serif; }
        .font-noto { font-family: 'Noto Serif', serif; }
        /* Animasi Fade In */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    {{-- TOMBOL KEMBALI (Sticky) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sticky top-20 z-30 pointer-events-none font-roboto">
        <a href="/dashboard" class="pointer-events-auto inline-flex items-center gap-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-gray-200 text-green-700 text-sm font-semibold rounded-full shadow-sm hover:shadow-md hover:bg-green-600 hover:text-white transition-all duration-300 group transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- ====================== LOGIKA TAMPILAN KOST ====================== --}}
    @if(strtolower($cabang->kategori_cabang) === 'kost')

    {{-- Tambahkan font-roboto di wrapper utama & pb-24 untuk jarak footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up font-roboto pb-24">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

            <div class="flex flex-col lg:flex-row">
                {{-- Bagian Gambar (Tidak Diubah) --}}
                <div class="relative w-full lg:w-5/12 h-[350px] lg:h-auto lg:min-h-[600px] overflow-hidden group">
                    @if ($cabang->nama_cabang === 'Pondok Satu')
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" src="{{ asset('images/pondok.jpg') }}" alt="Foto cabang" />
                    @elseif ($cabang->nama_cabang === 'Pondok Siti Hajar')
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" src="{{ asset('images/background.jpg') }}" alt="Foto cabang" />
                    @else
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105" src="{{ asset('images/background.jpg') }}" alt="Foto cabang" />
                    @endif

                    <div class="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent opacity-60"></div>

                    <div class="absolute top-6 left-6 z-10">
                        <span class="px-4 py-2 bg-white/95 backdrop-blur rounded-xl text-green-800 text-xs font-bold uppercase tracking-wider shadow-lg flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            {{ $cabang->kategori_cabang }}
                        </span>
                    </div>
                </div>

                <div class="w-full lg:w-7/12 p-6 sm:p-8 lg:p-12 flex flex-col bg-white relative">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-green-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-gray-900 tracking-tight mb-2 relative z-10">
                        {{ $cabang->nama_cabang }}
                    </h1>
                    <div class="w-24 h-1.5 bg-green-500 rounded-full mb-8"></div>

                    <div class="mb-8 relative z-10">
                        <h2 class="text-sm font-noto text-green-600 uppercase tracking-wide mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Deskripsi
                        </h2>
                        <p class="text-gray-600 font-noto leading-relaxed text-justify text-base lg:text-lg">
                            {{ $cabang->deskripsi }}
                        </p>
                    </div>

                    {{-- Landmark Sekitar (Dibuat Dinamis) --}}
                    <div class="mb-10 relative z-10">
                        <h3 class="text-sm font-noto font-bold text-gray-400 uppercase tracking-wide mb-4">Landmark Sekitar ({{ ucwords($cabang->lokasi) }})</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($landmarks as $place)
                                <div class="flex items-center p-3  font-noto font-bold rounded-xl bg-slate-50 hover:bg-green-50 hover:shadow-sm border border-transparent hover:border-green-100 transition-all duration-300 group cursor-default">
                                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-green-600 group-hover:scale-110 transition-transform">
                                        {{-- Logika Icon Sederhana --}}
                                        @if($place['icon'] == 'food')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.42584 18.2443c1.3169-2.2023 2.5334-1.5242 3.85026-3.7265 1.3169-2.2023.1004-2.8805 1.4173-5.08281 1.3169-2.20233 2.5335-1.52419 3.8504-3.72652M10.8472 20.1517c1.3169-2.2024 2.5334-1.5242 3.8503-3.7266 1.3169-2.2023.1004-2.8804 1.4173-5.0828 1.3169-2.20228 2.5334-1.52414 3.8503-3.72647l-6.8428-3.81455C11.8054 6.00361 10.5889 5.32547 9.272 7.5278s-.1004 2.8805-1.4173 5.0828c-1.3169 2.2023-2.5334 1.5242-3.85031 3.7265l6.84281 3.8146Z"/></svg>
                                        @elseif($place['icon'] == 'school')
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"></path><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"></path><path d="M18 5v17"></path><path d="m4 6 8-4 8 4"></path><path d="M6 5v17"></path><circle cx="12" cy="9" r="2"></circle></svg>
                                        @elseif($place['icon'] == 'ship')
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"></path><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"></path><path d="M12 10v4"></path><path d="M12 2v3"></path></svg>
                                        @else
                                            {{-- Default Icon (Park/General) --}}
                                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z"></path><path d="M7 16v6"></path><path d="M13 19v3"></path><path d="M12 19h8.3a1 1 0 0 0 .7-1.7L18 14h.3a1 1 0 0 0 .7-1.7L16 9h.2a1 1 0 0 0 .8-1.7L13 3l-1.4 1.5"></path></svg>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <p class="font-roboto text-gray-800 text-sm">{{ $place['name'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $place['dist'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-auto pt-6 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                        @php
                            $totalKamarTersedia = $cabang->kamars()->where('status', 'Tersedia')->count();
                        @endphp

                        <div class="text-center md:text-left">
                            <p class="text-xs font-bold font-roboto text-gray-400 uppercase mb-1">Ketersediaan Saat Ini</p>
                            <div class="flex items-baseline justify-center md:justify-start gap-1">
                                <span class="text-4xl font-black text-green-600 js-counter" data-value="{{ $totalKamarTersedia }}">0</span>
                                <span class="text-lg font-roboto font-black text-gray-600">Unit Tersedia</span>
                            </div>
                        </div>

                        <a href="{{ route('cabang.kamar.index', $cabang->route_params) }}"
                            class="w-full md:w-auto px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-600/20 transition-all duration-300 transform hover:-translate-y-1 flex items-center justify-center gap-2 group">
                            Pilih Kamar Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ====================== LOGIKA TAMPILAN VILLA (TIDAK DIUBAH SECARA VISUAL) ====================== --}}
   @elseif(strtolower($cabang->kategori_cabang) === 'villa')

    {{-- Penambahan class pb-24 agar footer tidak dempet --}}
    <div class="bg-gray-50 min-h-screen pb-24 fade-in-up">
        
        <!-- SIMPLE HEADER -->
        <div class="bg-green-600 py-8 text-center shadow-md">
            <div class="container mx-auto px-4">
                <h1 class="font-roboto text-3xl md:text-4xl font-black text-white tracking-wide uppercase mb-2">
                    {{ $cabang->nama_cabang }}
                </h1>
                
                <div class="flex flex-wrap justify-center items-center gap-4 text-green-100 font-roboto text-sm md:text-base">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        {{ $cabang->lokasi }}
                    </div>
                    <span class="opacity-50">|</span>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        {{ $cabang->kategori_cabang }}
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT GRID -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI (Deskripsi, Daftar Unit & Kolam Renang) -->
                <div class="lg:col-span-2 space-y-10">

                    <!-- SECTION: DESKRIPSI UTAMA -->
                    <div>
                        <h2 class="font-roboto text-2xl font-bold text-gray-800 mb-4 border-l-8 border-green-600 pl-4">
                            Tentang {{ $cabang->nama_cabang }}
                        </h2>
                        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                            <p class="font-noto text-gray-600 text-lg leading-relaxed text-justify">
                                {{ $cabang->deskripsi }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- SECTION: PILIHAN UNIT VILLA -->
                    <div>
                        <h2 class="font-roboto text-2xl font-bold text-gray-800 mb-6 flex items-center">
                            <span class="bg-green-600 w-1.5 h-6 mr-3 rounded-full"></span>
                            Pilihan Unit Villa
                        </h2>
                        
                        <div class="space-y-8">
                            <!-- UNIT 1: JASMINE (Contoh Tampilan Unit) -->
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transition hover:shadow-xl">
                                <!-- Galeri -->
                                <div class="grid grid-cols-3 gap-1 bg-gray-100">
                                    <img src="{{ asset('images/villa.jpg') }}" class="col-span-2 w-full h-48 object-cover">
                                    <div class="grid grid-rows-2 gap-1">
                                        <img src="{{ asset('images/villa.jpg') }}" class="w-full h-full object-cover">
                                        <img src="{{ asset('images/background.jpg') }}" class="w-full h-full object-cover">
                                    </div>
                                </div>
                                
                                <!-- Detail -->
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="font-roboto text-xl font-bold text-gray-900">Villa Jasmine</h3>
                                        <span class="bg-green-100 text-green-800 text-sm font-bold font-roboto px-3 py-1 rounded-full">Rp 400.000</span>
                                    </div>
                                    <p class="font-noto text-gray-600 text-sm leading-relaxed mb-4">
                                        Fasilitas lengkap (2 kamar tidur, AC, kipas angin, 2 WC, ruang tamu, ruang makan, dapur lengkap, kulkas, alat solat).
                                    </p>
                                    <div class="border-t border-gray-100 pt-3">
                                        <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Fasilitas Unggulan:</span>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Privasi Terjaga</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Homey</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Full Furnished</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- UNIT 2: ANGGREK (Contoh Tampilan Unit) -->
                            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 transition hover:shadow-xl">
                                <!-- Galeri -->
                                <div class="grid grid-cols-3 gap-1 bg-gray-100">
                                    <img src="{{ asset('images/villa.jpg') }}" class="col-span-2 w-full h-48 object-cover">
                                    <div class="grid grid-rows-2 gap-1">
                                        <img src="{{ asset('images/background.jpg') }}" class="w-full h-full object-cover">
                                        <img src="{{ asset('images/pondok.jpg') }}" class="w-full h-full object-cover">
                                    </div>
                                </div>

                                <!-- Detail -->
                                <div class="p-6">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="font-roboto text-xl font-bold text-gray-900">Villa Anggrek</h3>
                                        <span class="bg-green-100 text-green-800 text-sm font-bold font-roboto px-3 py-1 rounded-full">Rp 400.000</span>
                                    </div>
                                    <p class="font-noto text-gray-600 text-sm leading-relaxed mb-4">
                                        Fasilitas lengkap (2 kamar tidur, 2 WC, ruangan leluasa, AC, kipas, karpet jumbo, dapur lengkap, kulkas, alat solat).
                                    </p>
                                    <div class="border-t border-gray-100 pt-3">
                                        <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Fasilitas Unggulan:</span>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Ruang Luas</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Karpet Jumbo</span>
                                            <span class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">Rombongan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: FASILITAS UTAMA (KOLAM RENANG) -->
                    <div>
                        <h2 class="font-roboto text-2xl font-bold text-gray-800 mb-4 flex items-center">
                            <span class="bg-green-600 w-1.5 h-6 mr-3 rounded-full"></span>
                            Fasilitas Utama
                        </h2>
                        <div class="bg-white rounded-xl shadow-md overflow-hidden relative group">
                            <!-- Placeholder image -->
                            <img src="{{ asset('images/background.jpg') }}" class="w-full h-64 object-cover">
                            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent p-4 pt-10">
                                <h3 class="font-roboto text-xl text-white font-bold">Kolam Renang</h3>
                                <p class="font-noto text-sm text-gray-200">Area berenang yang bersih dan segar untuk dewasa maupun anak-anak.</p>
                            </div>
                        </div>
                    </div>

                </div>
                
                <!-- KOLOM KANAN (PANEL LAYANAN TAMBAHAN & BUTTON) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-6 lg:sticky lg:top-24">
                        
                        <!-- BUTTON ACTION GROUP -->
                        <div class="mb-8 space-y-3">
                            <a href="{{ route('cabang.kamar.index', $cabang->route_params) }}" class="block">
                                <button class="w-full bg-green-600 hover:bg-green-700 text-white font-roboto font-bold py-4 rounded-lg shadow-lg transform transition hover:-translate-y-1 text-lg flex justify-center items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Reservasi Sekarang
                                </button>
                            </a>
                            
                            <a href="{{ route('cabang.villa.detail', ['lokasi' => $lokasi, 'kategori' => $kategori]) }}" class="block">
                                <button class="w-full bg-white border-2 border-green-600 text-green-700 font-roboto font-bold py-3 rounded-lg hover:bg-green-50 transition text-sm flex justify-center items-center">
                                    Lihat Detail Lengkap
                                </button>
                            </a>

                            <p class="text-xs text-center text-gray-400 mt-2 font-noto">Reservasi mudah & cepat</p>
                        </div>

                        <h2 class="font-roboto text-xl font-bold text-gray-800 mb-4 border-b pb-2 border-gray-200">
                            Layanan Tambahan
                        </h2>
                        
                        <div class="space-y-4 font-noto">
                            {{-- Layanan Items (Tetap sama) --}}
                            <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                                <div class="w-14 h-14 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm">Sewa Gazebo</h4>
                                    <p class="text-xs text-gray-500 mb-1">Max 4 orang (4 Unit)</p>
                                    <div class="text-green-600 font-bold text-sm">Rp 50.000</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                                <div class="w-14 h-14 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm">Karaoke Music</h4>
                                    <p class="text-xs text-gray-500 mb-1">Sewa seharian</p>
                                    <div class="text-green-600 font-bold text-sm">Rp 150.000</div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                                <div class="w-14 h-14 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm">Karcis Masuk</h4>
                                    <p class="text-xs text-gray-500 mb-1">Dewasa & Anak</p>
                                    <div class="text-green-600 font-bold text-sm">Rp 5.000 <span class="text-xs text-gray-400 font-normal">/org</span></div>
                                </div>
                            </div>
                            <div class="flex items-start gap-3 last:border-0">
                                <div class="w-14 h-14 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm">Sewa Pelampung</h4>
                                    <p class="text-xs text-gray-500 mb-1">Tersedia di lokasi</p>
                                    <div class="text-blue-500 text-xs font-medium cursor-pointer hover:underline">Hubungi Petugas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endif

</div>

{{-- VANILLA JS --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. FADE IN ANIMATION
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    const fadeElements = document.querySelectorAll('.fade-in-up');
    fadeElements.forEach(el => observer.observe(el));

    // 2. NUMBER COUNTER ANIMATION (Hanya jalan jika elemen ada)
    const counters = document.querySelectorAll('.js-counter');
    if(counters.length > 0) {
        counters.forEach(counter => {
            const target = +counter.getAttribute('data-value');
            const duration = 1500;
            const start = performance.now();

            const animate = (currentTime) => {
                const elapsed = currentTime - start;
                const progress = Math.min(elapsed / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 4);

                counter.innerText = Math.floor(ease * target);

                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    counter.innerText = target;
                }
            };
            requestAnimationFrame(animate);
        });
    }
});
</script>

@endsection