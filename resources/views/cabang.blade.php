@extends('layout.master')
@section('title', $cabang->nama_cabang)
@php
    $lokasi = str_replace(' ', '-', strtolower($cabang->lokasi));
    $kategori = str_replace(' ', '-', strtolower($cabang->kategori_cabang));
@endphp
@section('content')
    {{-- TOMBOL KEMBALI (Dibuat Sticky agar mudah diakses saat scroll) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sticky top-20 z-30 pointer-events-none">
        <a href="/dashboard" class="pointer-events-auto inline-flex items-center gap-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-gray-200 text-green-700 text-sm font-semibold rounded-full shadow-sm hover:shadow-md hover:bg-green-600 hover:text-white transition-all duration-300 group transform hover:-translate-y-0.5">
            <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- ====================== LOGIKA TAMPILAN KOST ====================== --}}
    @if(strtolower($cabang->kategori_cabang) === 'kost')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 fade-in-up">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

            <div class="flex flex-col lg:flex-row">

                {{--
                    FIX GAMBAR HILANG:
                    Menggunakan flexbox (lg:w-5/12) alih-alih grid col-span yang kaku.
                    Menambahkan 'min-h' agar gambar tetap punya tinggi walau di desktop.
                --}}
                <div class="relative w-full lg:w-5/12 h-[350px] lg:h-auto lg:min-h-[600px] overflow-hidden group">
                    @if ($cabang->nama_cabang === 'Pondok Satu')
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                            src="{{ asset('images/pondok.jpg') }}" alt="Foto cabang" />
                    @elseif ($cabang->nama_cabang === 'Pondok Siti Hajar')
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                            src="{{ asset('images/background.jpg') }}" alt="Foto cabang" />
                    @else
                        <img class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105"
                            src="{{ asset('images/background.jpg') }}" alt="Foto cabang" />
                    @endif

                    {{-- Overlay Gradient agar tulisan terbaca jika ada --}}
                    <div class="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-transparent opacity-60"></div>

                    {{-- Badge Kategori --}}
                    <div class="absolute top-6 left-6 z-10">
                        <span class="px-4 py-2 bg-white/95 backdrop-blur rounded-xl text-green-800 text-xs font-bold uppercase tracking-wider shadow-lg flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            {{ $cabang->kategori_cabang }}
                        </span>
                    </div>
                </div>

                {{-- KOLOM KANAN: KONTEN --}}
                <div class="w-full lg:w-7/12 p-6 sm:p-8 lg:p-12 flex flex-col bg-white relative">

                    {{-- Dekorasi Background Abstrak --}}
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-green-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 tracking-tight mb-2 relative z-10">
                        {{ $cabang->nama_cabang }}
                    </h1>
                    <div class="w-24 h-1.5 bg-green-500 rounded-full mb-8"></div>

                    {{-- Deskripsi --}}
                    <div class="mb-8 relative z-10">
                        <h2 class="text-sm font-bold text-green-600 uppercase tracking-wide mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Deskripsi
                        </h2>
                        <p class="text-gray-600 leading-relaxed text-justify text-base lg:text-lg">
                            {{ $cabang->deskripsi }}
                        </p>
                    </div>

                    {{-- Tempat Terdekat (Grid Responsive) --}}
                    <div class="mb-10 relative z-10">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wide mb-4">Landmark Sekitar</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            {{-- List Item Landmark --}}
                            <div class="flex items-center p-3 rounded-xl bg-slate-50 hover:bg-green-50 hover:shadow-sm border border-transparent hover:border-green-100 transition-all duration-300 group cursor-default">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-green-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z"></path><path d="M7 16v6"></path><path d="M13 19v3"></path><path d="M12 19h8.3a1 1 0 0 0 .7-1.7L18 14h.3a1 1 0 0 0 .7-1.7L16 9h.2a1 1 0 0 0 .8-1.7L13 3l-1.4 1.5"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold text-gray-800 text-sm">Taman Mattirotasi</p>
                                    <p class="text-xs text-gray-500">800 m</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 rounded-xl bg-slate-50 hover:bg-green-50 hover:shadow-sm border border-transparent hover:border-green-100 transition-all duration-300 group cursor-default">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-green-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 22v-4a2 2 0 1 0-4 0v4"></path><path d="m18 10 4 2v8a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-8l4-2"></path><path d="M18 5v17"></path><path d="m4 6 8-4 8 4"></path><path d="M6 5v17"></path><circle cx="12" cy="9" r="2"></circle></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold text-gray-800 text-sm">ITBJ Habibie</p>
                                    <p class="text-xs text-gray-500">1 km</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 rounded-xl bg-slate-50 hover:bg-green-50 hover:shadow-sm border border-transparent hover:border-green-100 transition-all duration-300 group cursor-default">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-green-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.42584 18.2443c1.3169-2.2023 2.5334-1.5242 3.85026-3.7265 1.3169-2.2023.1004-2.8805 1.4173-5.08281 1.3169-2.20233 2.5335-1.52419 3.8504-3.72652M10.8472 20.1517c1.3169-2.2024 2.5334-1.5242 3.8503-3.7266 1.3169-2.2023.1004-2.8804 1.4173-5.0828 1.3169-2.20228 2.5334-1.52414 3.8503-3.72647l-6.8428-3.81455C11.8054 6.00361 10.5889 5.32547 9.272 7.5278s-.1004 2.8805-1.4173 5.0828c-1.3169 2.2023-2.5334 1.5242-3.85031 3.7265l6.84281 3.8146Z"/></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold text-gray-800 text-sm">Mie Gacoan</p>
                                    <p class="text-xs text-gray-500">1.2 km</p>
                                </div>
                            </div>

                            <div class="flex items-center p-3 rounded-xl bg-slate-50 hover:bg-green-50 hover:shadow-sm border border-transparent hover:border-green-100 transition-all duration-300 group cursor-default">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm text-green-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1 .6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1"></path><path d="M19.38 20A11.6 11.6 0 0 0 21 14l-9-4-9 4c0 2.9.94 5.34 2.81 7.76"></path><path d="M19 13V7a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v6"></path><path d="M12 10v4"></path><path d="M12 2v3"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="font-bold text-gray-800 text-sm">Pelabuhan</p>
                                    <p class="text-xs text-gray-500">1.5 km</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Bar (Bawah) --}}
                    <div class="mt-auto pt-6 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                        @php
                            $totalKamarTersedia = $cabang->kamars()->where('status', 'Tersedia')->count();
                        @endphp

                        <div class="text-center md:text-left">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Ketersediaan Saat Ini</p>
                            <div class="flex items-baseline justify-center md:justify-start gap-1">
                                <span class="text-4xl font-black text-green-600 js-counter" data-value="{{ $totalKamarTersedia }}">0</span>
                                <span class="text-lg font-medium text-gray-600">Unit Tersedia</span>
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


    {{-- ====================== LOGIKA TAMPILAN VILLA ====================== --}}
   @elseif(strtolower($cabang->kategori_cabang) === 'villa')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 fade-in-up">
        
        {{-- CONTAINER UTAMA: Desain Card Besar dengan Sudut Melengkung --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-gray-100 ring-1 ring-black/5">

            {{-- 1. HERO SECTION (GAMBAR UTAMA) --}}
            <div class="relative h-[65vh] min-h-[500px] w-full group overflow-hidden">
                {{-- Gambar --}}
              
                    <img src="{{ asset('images/villa.jpg') }}"
                         alt="Default Villa"
                         class="w-full h-full object-cover object-center transition-transform duration-1000 ease-out group-hover:scale-105">

                {{-- Overlay Gradient (Agar teks terbaca jelas) --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

                {{-- Badge Lokasi (Pojok Kiri Atas) --}}
                <div class="absolute top-8 left-8 z-10">
                    <div class="flex items-center gap-2">
                        <span class="px-4 py-2 bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-widest rounded-full shadow-lg">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $cabang->lokasi }}
                        </span>
                    </div>
                </div>

                {{-- Judul & Tagline (Pojok Kiri Bawah) --}}
                <div class="absolute bottom-0 left-0 w-full p-8 md:p-12 lg:p-16">
                    <p class="text-green-400 font-medium tracking-widest uppercase mb-2 text-sm">Exclusive Villa Collection</p>
                    <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold text-white font-serif mb-4 drop-shadow-lg leading-tight">
                        {{ $cabang->nama_cabang }}
                    </h1>
                    <div class="h-1 w-20 bg-green-500 rounded-full mb-6"></div>
                    
                    {{-- Harga (Display Visual Saja) --}}
                    <div class="flex items-end gap-2 text-white/90">
                        <span class="text-lg">Mulai dari</span>
                        <span class="text-3xl font-bold text-white">IDR 500k++</span>
                        <span class="text-sm text-gray-300 mb-1">/ malam</span>
                    </div>
                </div>
            </div>

            {{-- 2. KONTEN DETAIL (GRID SYSTEM) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                
                {{-- KOLOM KIRI: Deskripsi & Preview Kamar (Lebih Lebar) --}}
                <div class="lg:col-span-2 p-8 md:p-12 lg:p-16 bg-white">
                    
                    {{-- Deskripsi Text --}}
                    <div class="mb-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 font-serif flex items-center gap-3">
                            Tentang Villa
                        </h3>
                        <p class="text-gray-600 text-lg leading-relaxed text-justify font-light">
                            {{ $cabang->deskripsi }}
                        </p>
                    </div>

                    {{-- Tombol Aksi (CTA) --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        {{-- Tombol ini nanti akan diarahkan ke route daftar kamar --}}
                        <a href="{{ route('cabang.kamar.index', $cabang->route_params) }}"
                        <button class="flex-1 px-8 py-4 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-900 transition shadow-xl hover:shadow-2xl transform hover:-translate-y-1 flex justify-center items-center gap-2">
                            <span>Reservasi Sekarang</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                        </a>
                        
                        <a href="{{ route('cabang.villa.detail', ['lokasi' => $lokasi, 'kategori' => $kategori]) }}"
                        <button class="flex-1 px-8 py-4 border-2 border-gray-200 text-center text-gray-700 font-semibold rounded-xl hover:border-gray-900 hover:text-gray-900 transition bg-transparent">
                            Lihat Detail
                        </button>
                        </a>
                    </div>
                </div>

                {{-- KOLOM KANAN: Sidebar Fasilitas (Background Abu-abu halus) --}}
                <div class="bg-gray-50 border-l border-gray-100 p-8 md:p-12 lg:p-16 flex flex-col h-full">
                    <h4 class="font-bold text-gray-900 mb-8 uppercase tracking-widest text-xs border-b pb-4 border-gray-200">
                        Fasilitas & Layanan
                    </h4>
                    
                    {{-- List Fasilitas dengan Icon --}}
                    <ul class="space-y-6 mb-10">
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800 text-sm">Private Property</span>
                                <span class="text-xs text-gray-500">Privasi terjaga untuk Anda</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800 text-sm">{{ $cabang->jumlah_kamar }} Unit Kamar</span>
                                <span class="text-xs text-gray-500">Tersedia untuk disewa</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-green-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <span class="block font-bold text-gray-800 text-sm">Resepsionis 24 Jam</span>
                                <span class="text-xs text-gray-500">Siap membantu kapan saja</span>
                            </div>
                        </li>
                    </ul>

                    {{-- Info Box --}}
                    <div class="mt-auto bg-green-50 p-6 rounded-2xl border border-green-100">
                        <p class="font-serif text-lg font-bold text-green-800 mb-2">Need Help?</p>
                        <p class="text-sm text-green-700/80 mb-4 leading-relaxed">
                            Hubungi layanan pelanggan kami jika Anda memiliki pertanyaan spesifik mengenai villa ini.
                        </p>
                        <button class="w-full py-2 bg-white text-green-700 font-bold text-xs uppercase tracking-wider rounded-lg shadow-sm hover:shadow-md transition">
                            Chat WhatsApp
                        </button>
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
    // Membuat elemen muncul perlahan saat di-scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target); // Hanya animasi sekali
            }
        });
    }, { threshold: 0.1 });

    const fadeElements = document.querySelectorAll('.fade-in-up');
    fadeElements.forEach(el => observer.observe(el));

    // 2. NUMBER COUNTER ANIMATION
    // Menghitung angka dari 0 ke total kamar
    const counters = document.querySelectorAll('.js-counter');
    counters.forEach(counter => {
        const target = +counter.getAttribute('data-value');
        const duration = 1500;
        const start = performance.now();

        const animate = (currentTime) => {
            const elapsed = currentTime - start;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function (easeOutQuart) agar melambat di akhir
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
});
</script>

{{-- STYLE TAMBAHAN UNTUK ANIMASI --}}
<style>
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

@endsection
