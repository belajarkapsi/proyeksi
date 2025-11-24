{{-- resources/views/detail-kamar.blade.php --}}
@extends('layout.master')
@section('title', isset($cabang) ? 'Detail Kamar No ' . $cabang->nama_cabang : 'Semua Daftar Kamar')

@section('content')
    <div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Breadcrumb Modern --}}
        <nav class="flex items-center text-sm text-gray-500 mb-6 overflow-x-auto whitespace-nowrap pb-2">
            <a href="{{ route('dashboard') }}" class="hover:text-green-600 transition-colors flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('cabang.show', $cabang->route_params) }}" class="hover:text-green-600 transition-colors">{{ $cabang->nama_cabang }}</a>
            <span class="mx-2 text-gray-300">/</span>
            <a href="{{ route('cabang.kamar.index', $cabang->route_params) }}" class="hover:text-green-600 transition-colors">Daftar Kamar</a>
            <span class="mx-2 text-gray-300">/</span>
            <span class="text-green-700 font-medium bg-green-50 px-2 py-0.5 rounded-full">Kamar {{ $room->number ?? '101' }}</span>
        </nav>

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
                            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tipe Kamar {{ $room->number ?? '101' }}</h1>
                            <p class="text-gray-500 flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Pondok Siti Hajar, Lantai {{ substr($room->number ?? '101', 0, 1) }}
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
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Fasilitas Kamar
                    </h3>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-6">
                        @php
                            $fasilitas = $room->facilities ?? ['Kasur Springbed','Bantal & Guling','Kipas Angin','Kamar mandi dalam', 'Lemari Pakaian', 'Meja Belajar'];
                        @endphp
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
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Fasilitas Umum</h3>
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
                        <h3 class="text-lg font-bold text-red-800 mb-4">Peraturan Kost</h3>
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

            {{-- KOLOM KANAN: Sidebar Harga (Sticky) --}}
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">

                    {{-- Card Harga Desktop --}}
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hidden lg:block">
                        <div class="bg-green-600 px-6 py-4">
                            <p class="text-green-100 text-sm font-medium mb-1">Harga Sewa Mulai</p>
                            <p class="text-white text-3xl font-bold">
                                Rp {{ number_format($room->price_per_month ?? 300000, 0, ',', '.') }}
                                <span class="text-sm font-normal text-green-200">/ bulan</span>
                            </p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-2">
                                    <span class="text-gray-500">Harian</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_day ?? 125000, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm border-b border-dashed border-gray-200 pb-2">
                                    <span class="text-gray-500">Mingguan</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_week ?? 500000, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Tahunan</span>
                                    <span class="font-semibold text-gray-800">Rp {{ number_format($room->price_per_year ?? 5000000, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <button class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3.5 rounded-xl shadow-md transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Ajukan Sewa
                            </button>

                            <a href="https://wa.me/6281234567890" class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-bold py-3 rounded-xl border border-green-200 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Tanya Pemilik
                            </a>
                        </div>
                    </div>

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

        <div id="booking-form"></div>
    </div>

    {{-- MOBILE BOTTOM BAR (Hanya muncul di HP) --}}
    <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-4 lg:hidden z-40 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex items-center justify-between gap-4 max-w-7xl mx-auto">
            <div>
                <p class="text-xs text-gray-500">Harga per bulan</p>
                <p class="text-xl font-bold text-green-600">Rp {{ number_format($room->price_per_month ?? 300000, 0, ',', '.') }}</p>
            </div>
            <button class="bg-gray-900 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg flex-1">
                Ajukan Sewa
            </button>
        </div>
    </div>

    {{-- LIGHTBOX MODAL (Pop up gambar) --}}
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center opacity-0 transition-opacity duration-300">
        <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white hover:text-gray-300 p-2">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="lightbox-img" src="" class="max-h-[90vh] max-w-[90vw] rounded-lg shadow-2xl transform scale-95 transition-transform duration-300">
    </div>

</div>

@push('scripts')
{{-- JAVASCRIPT (Vanilla JS) --}}
<script>
    // Fungsi Lightbox
    function openLightbox(imageSrc) {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');

        img.src = imageSrc;
        lightbox.classList.remove('hidden');

        // Sedikit delay agar animasi opacity berjalan
        setTimeout(() => {
            lightbox.classList.remove('opacity-0');
            img.classList.remove('scale-95');
            img.classList.add('scale-100');
        }, 10);

        // Disable scroll body
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

    // Tutup lightbox jika klik di area hitam (luar gambar)
    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLightbox();
        }
    });
</script>
@endpush

@endsection
