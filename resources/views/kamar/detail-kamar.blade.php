@extends('layout.master')
@section('title', isset($cabang) ? 'Detail ' . ($cabang->kategori_cabang == 'Villa' ? 'Unit Villa' : 'Kamar Kost') : 'Detail Kamar')

@section('content')

    {{-- LOAD FONTS (Global) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 pt-6">

        {{-- Breadcrumb Modern (Global) --}}
        <nav class="flex items-center text-sm text-gray-500 mb-6 overflow-x-auto whitespace-nowrap pb-2 font-roboto">
            <a href="{{ route('dashboard') }}" class="hover:text-green-600 transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('cabang.show', $cabang->route_params) }}" class="hover:text-green-600 transition-colors">{{ $cabang->nama_cabang }}</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('cabang.kamar.index', $cabang->route_params) }}" class="hover:text-green-600 transition-colors">Daftar Unit</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-green-700 font-medium bg-green-50 px-2 py-0.5 rounded-full">Unit {{ $room->number ?? $room->no_kamar ?? '101' }}</span>
        </nav>

        {{-- ======================================================================= --}}
        {{--                         TAMPILAN KHUSUS VILLA                           --}}
        {{-- ======================================================================= --}}
        @if(strtolower($cabang->kategori_cabang) === 'villa')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            {{-- KOLOM KIRI (Gallery & Detail) --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- 1. Header & Title --}}
                <div class="border-b border-gray-200 pb-4">
                    <h1 class="font-roboto text-3xl md:text-4xl font-black text-gray-900 mb-2">
                        {{ $cabang->nama_cabang }} - Unit {{ $room->no_kamar ?? 'Anggrek' }}
                    </h1>
                    <p class="font-noto text-gray-600 text-lg">
                        Nikmati liburan nyaman dengan fasilitas lengkap untuk keluarga.
                    </p>
                </div>

                {{-- 2. Gallery Section (Style Lebar) --}}
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 group">
                    <div class="relative h-[400px] md:h-[500px] overflow-hidden cursor-zoom-in" onclick="openLightbox('{{ asset($room->image ?? 'images/villa.jpg') }}')">
                        <img src="{{ asset($room->image ?? 'images/villa.jpg') }}" 
                             alt="Foto Villa" 
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-60"></div>
                        <div class="absolute bottom-6 left-6 text-white">
                            <span class="bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">
                                {{ $room->type ?? 'Family Room' }}
                            </span>
                            <p class="text-sm font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Lihat Semua Foto
                            </p>
                        </div>
                    </div>
                </div>

                {{-- 3. Deskripsi & Fasilitas --}}
                <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-gray-100">
                    <h3 class="font-roboto text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="bg-green-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        Fasilitas Unit
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8 font-noto text-sm text-gray-700">
                        @php
                            $fasilitasVilla = $room->facilities ?? ['2 Kamar Tidur', 'Full AC', 'Dapur Lengkap', 'Kulkas', 'Ruang Tamu Luas', 'TV LED', 'Alat BBQ', 'Parkir Luas'];
                        @endphp
                        @foreach($fasilitasVilla as $fv)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $fv }}
                        </div>
                        @endforeach
                    </div>

                    <h3 class="font-roboto text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <span class="bg-green-600 w-1.5 h-6 mr-3 rounded-full"></span>
                        Deskripsi
                    </h3>
                    <p class="font-noto text-gray-600 leading-relaxed text-justify">
                        {{ $room->description ?? 'Unit Villa ini didesain untuk kenyamanan keluarga besar atau rombongan. Dilengkapi dengan ruang tengah yang luas untuk berkumpul, dapur yang bisa digunakan untuk memasak, serta akses langsung ke area kolam renang umum.' }}
                    </p>
                </div>
            </div>

            {{-- KOLOM KANAN (PANEL MIRIP HALAMAN CABANG) --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-xl border border-gray-100 p-6 sticky top-24">
                    
                    {{-- Harga & Button --}}
                    <div class="mb-8 space-y-4 text-center">
                        <div>
                            <p class="text-sm font-roboto text-gray-500 mb-1">Harga Sewa per Malam</p>
                            <div class="text-4xl font-black text-green-600 font-roboto">
                                Rp {{ number_format($room->price_per_day ?? 400000, 0, ',', '.') }}
                            </div>
                        </div>

                        <!-- Button Action -->
                        <a href="{{ route('booking.checkout', ['kamar' => $room->id_kamar ?? $room->no_kamar]) }}"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-roboto font-bold py-4 rounded-lg shadow-lg transform transition hover:-translate-y-1 text-lg flex justify-center items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Reservasi Sekarang
                        </a>

                        
                        <a href="https://wa.me/6281234567890" class="block w-full text-center text-green-600 font-bold text-sm hover:underline">
                            Tanya Ketersediaan via WhatsApp
                        </a>
                    </div>

                    {{-- Layanan Tambahan (Copy dari layout Cabang) --}}
                    <h2 class="font-roboto text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-200">
                        Layanan Tambahan
                    </h2>
                    
                    <div class="space-y-4 font-noto">
                        <!-- Item 1: Gazebo -->
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-10 h-10 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Sewa Gazebo</h4>
                                <div class="text-green-600 font-bold text-xs">Rp 50.000</div>
                            </div>
                        </div>

                        <!-- Item 2: Karaoke -->
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-10 h-10 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Karaoke Music</h4>
                                <div class="text-green-600 font-bold text-xs">Rp 50.000</div>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="w-10 h-10 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path fill="currentColor" d="M21 5c-1.11-.35-2.33-.5-3.5-.5C13.17 4 9.14 5.96 7 8s-2.17 4-5.5 4c1.11.35 2.33.5 3.5.5C10.83 12.5 14.86 10.54 17 8s2.17-4 5.5-4c-1.11.35-2.33.5-3.5.5zM17 9c-1.38 0-2.5 1.12-2.5 2.5S15.62 14 17 14s2.5-1.12 2.5-2.5S18.38 9 17 9z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Kolam Renang</h4>
                                <div class="text-green-600 font-bold text-xs">Rp 20.000</div>
                            </div>
                        </div>

                        <!-- Item 3: Pelampung -->
                        <div class="flex items-start gap-3 last:border-0">
                            <div class="w-10 h-10 rounded-md bg-green-50 flex items-center justify-center flex-shrink-0 text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 text-sm">Sewa Pelampung</h4>
                                <div class="text-blue-500 text-xs font-medium cursor-pointer hover:underline">Hubungi Petugas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>


        {{-- ======================================================================= --}}
        {{--                         TAMPILAN DEFAULT (KOST)                         --}}
        {{-- ======================================================================= --}}
        @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KOLOM KIRI: Konten Utama --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- 1. Gallery Section (Mosaic Layout) --}}
                <div class="bg-white rounded-2xl shadow-sm p-2 border border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-2 h-[400px] md:h-[450px]">
                        {{-- Main Image (Besar Kiri) --}}
                        <div class="md:col-span-3 relative group overflow-hidden rounded-xl cursor-zoom-in" onclick="openLightbox('{{ asset($room->image ?? 'images/kamar.jpg') }}')">
                            <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}"
                                 alt="Kamar {{ $room->number ?? '' }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                        </div>

                        {{-- Small Images (Kanan Stacked) --}}
                        <div class="hidden md:grid grid-rows-2 gap-2">
                            <div class="relative group overflow-hidden rounded-xl cursor-zoom-in" onclick="openLightbox('{{ asset('images/kamar.jpg') }}')">
                                <img src="{{ asset('images/kamar.jpg') }}" alt="Kamar View 2" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </div>
                            <div class="relative group overflow-hidden rounded-xl cursor-zoom-in" onclick="openLightbox('{{ asset('images/kamar.jpg') }}')">
                                <img src="{{ asset('images/kamar.jpg') }}" alt="Kamar View 3" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                {{-- Overlay "Lihat Semua" (Opsional) --}}
                                <div class="absolute inset-0 bg-black/30 hover:bg-black/40 flex items-center justify-center transition-colors">
                                    <span class="text-white text-xs font-medium border border-white/50 px-2 py-1 rounded-md backdrop-blur-sm">+ Foto Lainnya</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Header Title --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8 border border-gray-100">
                    <div class="flex flex-wrap justify-between items-start gap-4 mb-6"> 
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2 font-roboto">Tipe Kamar {{ $room->no_kamar ?? '101' }}</h1>
                            <p class="text-gray-500 flex items-center gap-2 text-sm font-roboto">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $cabang->nama_cabang }}, Lantai {{ substr($room->no_kamar ?? '101', 0, 1) }}
                            </p>
                        </div>
                        <div>
                            @if($room->available ?? true)
                                <span class="inline-flex items-center gap-1.5 bg-green-100 text-green-800 px-4 py-1.5 rounded-full text-sm font-semibold border border-green-200">
                                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-800 px-4 py-1.5 rounded-full text-sm font-semibold border border-red-200">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Terisi
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr class="border-gray-100 mb-6">

                    {{-- Spesifikasi Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="p-2 bg-white rounded-lg text-gray-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Ukuran</p>
                                <p class="text-gray-800 font-medium">{{ $room->size ?? '4 x 5 meter' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="p-2 bg-white rounded-lg text-gray-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold">Listrik</p>
                                <p class="text-gray-800 font-medium">{{ $room->electricity ?? 'Termasuk listrik' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Fasilitas --}}
                <div class="bg-white rounded-2xl shadow-sm p-6 sm:p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2 font-roboto">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Fasilitas Kamar
                    </h3>

                    @php
                        // Default list jika tidak ada data fasilitas di DB
                        $defaultFacilities = ['Kasur Springbed','Bantal & Guling','Kipas Angin','Kamar mandi dalam', 'Lemari Pakaian', 'Meja Belajar'];

                        // PRIORITAS: ambil tipe dari query param 'tipe' jika ada, fallback ke model
                        $roomType = strtolower(trim(request('tipe') ?? $room->tipe_kamar ?? $room->type ?? ''));

                        // Jika tipe Standar, ganti 'Kipas Angin' menjadi 'AC' (case-insensitive)
                        if ($roomType === 'standar') {
                            $fasilitas = collect($room->facilities ?? $defaultFacilities)
                                ->map(function($item){
                                    return (strtolower(trim($item)) === 'kipas angin') ? 'AC' : $item;
                                })->toArray();
                        } else {
                            // Ekonomis atau lainnya: gunakan fasilitas apa adanya (DB atau default)
                            $fasilitas = $room->facilities ?? $defaultFacilities;
                        }
                    @endphp

                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6">
                        @foreach($fasilitas as $f)
                            <li class="flex items-start gap-3 text-gray-700">
                                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $f }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- 4. Fasilitas Umum & Peraturan (Grid 2 Kolom) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fasilitas Umum --}}
                    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 font-roboto">Fasilitas Umum</h3>
                        <ul class="space-y-2">
                            @php
                                $common = $room->common_facilities ?? ['R. Jemuran Luas', 'Parkiran Motor Aman', 'Dapur Umum', 'CCTV 24 Jam'];
                            @endphp
                            @foreach($common as $c)
                                <li class="flex items-center gap-2 text-gray-600 text-sm">
                                    <div class="w-1.5 h-1.5 bg-blue-400 rounded-full"></div>
                                    {{ $c }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Peraturan --}}
                    <div class="bg-red-50 rounded-2xl shadow-sm p-6 border border-red-100">
                        <h3 class="text-lg font-bold text-red-800 mb-4 font-roboto">Peraturan Kost</h3>
                        <ul class="space-y-2">
                            @php
                                $rules = $room->rules ?? [
                                    'Tamu boleh menginap (Lapor)',
                                    'Tamu menginap dikenakan biaya',
                                    'Maks. 2 orang / kamar',
                                    'Tidak untuk pasangan non-pasutri'
                                ];
                            @endphp
                            @foreach($rules as $r)
                                <li class="flex items-start gap-2 text-red-700 text-sm">
                                    <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                    {{ $r }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: Sidebar Harga Kost (Sticky) --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">

                    {{-- Card Harga Desktop (DIPISAHKAN BERDASARKAN TIPE KAMAR) --}}
                    @php
                        // Gunakan $roomType yang sudah dihitung di atas (prioritaskan request('tipe'))
                        // Fallback harga - kamu bisa atur di model/db: price_per_month_ekonomis / price_per_month_standar
                        $fallbackEkonomis = 500000;
                        $fallbackStandar = 700000;
                    @endphp

                    @if($roomType === 'standar')
                        {{-- Panel Harga untuk Kamar Standar --}}
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hidden lg:block">
                            <div class="bg-green-600 px-6 py-4">
                                <p class="text-green-100 text-sm font-medium mb-1">Harga Sewa - Standar</p>
                                <p class="text-white text-3xl font-bold font-roboto">
                                    Rp {{ number_format($room->price_per_month_standar ?? $room->price_per_month ?? $fallbackStandar, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-green-200">/ bulan</span>
                                </p>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-2">
                                        <span class="text-gray-500">Harian</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_day_standar ?? $room->price_per_day ?? 150000, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-2">
                                        <span class="text-gray-500">Mingguan</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_week_standar ?? $room->price_per_week ?? 300000, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Tahunan</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_year_standar ?? $room->price_per_year ?? 7000000, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('booking.checkout', [
        'kamar' => $room->id_kamar ?? $room->no_kamar,
        'tipe'  => $roomType
    ]) }}"
   class="w-full bg-green-900 hover:bg-green-800 text-white font-bold py-3.5 rounded-xl shadow-md transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"/>
    </svg>
    Pesan Sekarang
</a>


                                <a href="https://wa.me/6281234567890" class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-bold py-3 rounded-xl border border-green-200 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-..."/></svg>
                                    Tanya Pemilik
                                </a>
                            </div>
                        </div>
                    @else
                        {{-- Panel Harga untuk Kamar Ekonomis (default) --}}
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hidden lg:block">
                            <div class="bg-green-600 px-6 py-4">
                                <p class="text-green-100 text-sm font-medium mb-1">Harga Sewa - Ekonomis</p>
                                <p class="text-white text-3xl font-bold font-roboto">
                                    Rp {{ number_format($room->price_per_month_ekonomis ?? $room->price_per_month ?? $fallbackEkonomis, 0, ',', '.') }}
                                    <span class="text-sm font-normal text-green-200">/ bulan</span>
                                </p>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-2">
                                        <span class="text-gray-500">Harian</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_day_ekonomis ?? $room->price_per_day ?? 125000, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-2">
                                        <span class="text-gray-500">Mingguan</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_week_ekonomis ?? $room->price_per_week ?? 250000, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Tahunan</span>
                                        <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_year_ekonomis ?? $room->price_per_year ?? 5500000, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('booking.checkout', [
        'kamar' => $room->id_kamar ?? $room->no_kamar,
        'tipe'  => $roomType
    ]) }}"
   class="w-full bg-green-900 hover:bg-green-800 text-white font-bold py-3.5 rounded-xl shadow-md transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 002 2z"/>
    </svg>
    Pesan Sekarang
</a>


                                <a href="https://wa.me/6281234567890" class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-bold py-3 rounded-xl border border-green-200 transition-colors flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-..."/></svg>
                                    Tanya Pemilik
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Bantuan --}}
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 hidden lg:block">
                        <div class="flex gap-3">
                            <div class="bg-blue-100 p-2 rounded-full h-fit text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-900">Butuh Bantuan?</p>
                                <p class="text-xs text-blue-700 mt-1">Jika ragu, silakan hubungi admin untuk konfirmasi ketersediaan.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
        
        @endif

        {{-- MOBILE BOTTOM BAR (Hanya muncul di HP) --}}
        {{-- Logika: Jika Villa tampilkan harian, Jika Kost tampilkan bulanan --}}
        <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 lg:hidden z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="flex items-center justify-between gap-4 max-w-7xl mx-auto">
                <div>
                    @if(strtolower($cabang->kategori_cabang) === 'villa')
                        <p class="text-xs text-gray-500 font-roboto">Harga per malam</p>
                        <p class="text-xl font-bold text-green-600 font-roboto">Rp {{ number_format($room->price_per_day ?? 400000, 0, ',', '.') }}</p>
                    @else
                        <p class="text-xs text-gray-500 font-roboto">Harga per bulan</p>
                        <p class="text-xl font-bold text-green-600 font-roboto">Rp {{ number_format($room->price_per_month ?? 300000, 0, ',', '.') }}</p>
                    @endif
                </div>
                <a href="{{ route('booking.checkout', ['kamar' => $room->id_kamar ?? $room->no_kamar]) }}"
   class="bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg flex-1 font-roboto text-center">
    {{ strtolower($cabang->kategori_cabang) === 'villa' ? 'Reservasi' : 'Ajukan Sewa' }}
</a>

            </div>
        </div>

    </div>

    {{-- LIGHTBOX MODAL (Global) --}}
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center opacity-0 transition-opacity duration-300">
        <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white hover:text-gray-300 p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="lightbox-img" src="" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-2xl transform scale-95 transition-transform duration-300">
    </div>

@endsection

@push('scripts')
<script>
    function openLightbox(imageSrc) {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');

        img.src = imageSrc;
        lightbox.classList.remove('hidden');

        setTimeout(() => {
            lightbox.classList.remove('opacity-0');
            img.classList.remove('scale-95');
            img.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');

        lightbox.classList.add('opacity-0');
        img.classList.remove('scale-100');
        img.classList.add('scale-95');

        setTimeout(() => {
            lightbox.classList.add('hidden');
            img.src = '';
            document.body.style.overflow = 'auto';
        }, 300);
    }

    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
</script>
@endpush
