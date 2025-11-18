@extends('layout.master')

@section('content')

<section class="relative h-[350px] md:h-[450px] lg:h-[500px] overflow-hidden">
    {{-- Background --}}
    <img src="{{ asset('images/background.jpg') }}" 
         class="absolute inset-0 w-full h-full object-cover opacity-50" 
         alt="Hero Background">

    {{-- Overlay Content --}}
    <div class="container mx-auto px-6 relative z-20 h-full flex flex-col justify-center">
        
        {{-- Judul + Deskripsi --}}
        <div class="max-w-xl mx-auto md:mx-0">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold font-serif leading-tight text-gray-900 text-center md:text-left">
                Penginapan Murah<br>&amp;<br>Terjangkau
            </h1>

            <p class="mt-3 text-sm md:text-base text-green-900/90 max-w-[420px] mx-auto md:mx-0 text-center md:text-left">
                Penginapan kami memadukan fasilitas lengkap, kenyamanan, dan harga terjangkau. 
                Didukung pelayanan ramah dan area yang aman, setiap kebutuhan menginap Anda terpenuhi tanpa kompromi.
            </p>
        </div>

        {{-- Tombol SELALU di Tengah --}}
        <div class="mt-6 flex justify-center">
            <a href="#area-penginapan"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg shadow-lg">
                Jelajahi sekarang
            </a>
        </div>
    </div>

    {{-- Dua Gambar Room di Kanan --}}

{{-- === GALLERY KANAN DENGAN SCROLL + AUTO ZOOM === --}}
<div class="hidden md:block absolute right-6 top-1/2 -translate-y-1/2 z-40">
    <div class="w-[270px]"> {{-- container luar --}}
        
        {{-- scroll area --}}
        <div id="heroGallery"
             class="overflow-x-auto flex gap-6 px-2 py-2 scrollbar-hide snap-x snap-mandatory">
            
            {{-- ITEM 1 --}}
            <div class="gallery-item flex-shrink-0 snap-center 
                        w-[250px] h-[350px] rounded-xl overflow-hidden 
                        border-4 border-white/70 shadow-xl bg-white
                        transition-transform duration-300 ease-out scale-75">
                <img src="{{ asset('images/background.jpg') }}"
                     alt="Room 1"
                     class="w-full h-full object-cover">
            </div>

            {{-- ITEM 2 --}}
            <div class="gallery-item flex-shrink-0 snap-center 
                        w-[250px] h-[350px] rounded-xl overflow-hidden 
                        border-4 border-white/70 shadow-xl bg-white
                        transition-transform duration-300 ease-out scale-75">
                <img src="{{ asset('images/villa.jpg') }}"
                     alt="Room 2"
                     class="w-full h-full object-cover">
            </div>

            {{-- ITEM 3 --}}
            <div class="gallery-item flex-shrink-0 snap-center 
                        w-[250px] h-[350px] rounded-xl overflow-hidden 
                        border-4 border-white/70 shadow-xl bg-white
                        transition-transform duration-300 ease-out scale-75">
                <img src="{{ asset('images/pondok.jpg') }}"
                     alt="Room 2"
                     class="w-full h-full object-cover">
            </div>

            {{-- TAMBAH ITEM LAIN JIKA PERLU --}}
        </div>
    </div>
</div>

<style>
/* Hide scrollbar */
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
@vite(['resources/js/app.js', 'resources/css/app.css'])
</script>

</section>


  {{-- small thumbnail on right (decor) --}}
  <div class="hidden md:block absolute right-8 top-6 w-44 h-36 rounded-lg overflow-hidden border-8 border-white/60 shadow-2xl z-30">
    <img src="{{ asset('images/hero-thumb.jpg') }}" alt="Hero thumb" class="w-full h-full object-cover">
  </div>
</section>


{{-- ABOUT COMPANY --}}
<section class="container mx-auto px-5 py-20 flex flex-col md:flex-row items-center gap-10">
    
    <img src="{{ asset('images/penginapan.jpg') }}" 
         class="w-full md:w-1/2 rounded-lg shadow-xl">

    <div class="md:w-1/2">
        <h2 class="text-2xl font-bold font-serif mb-4 text-green-700">About our company</h2>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua.
        </p>

        <p class="mt-4">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua.
        </p>
    </div>

</section>


{{-- AREA PENGINAPAN --}}
<section id="area-penginapan" class="py-16">
    {{-- Judul Section --}}
    <div class="relative text-center mb-10">
        <h2 class="text-[64px] font-extrabold font-serif text-gray-200 select-none absolute inset-0 flex justify-center items-center pointer-events-none">
            Area Penginapan
        </h2>

        <h3 class="relative text-3xl md:text-3xl font-extrabold font-serif text-gray-900">
            Area Penginapan
        </h3>
    </div>

    {{-- Grid Cards --}}
    <div class="container mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl">

        {{-- CARD 1 --}}
        <div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100">
            
            {{-- Gambar + Badge --}}
            <div class="relative h-48 w-full">
                <img src="{{ asset('images/background.jpg') }}" 
                     class="w-full h-full object-cover" alt="Penginapan">

                <span class="absolute top-2 left-2 bg-white text-gray-800 text-xs px-3 py-1 rounded-full shadow">
                    ● PAREPARE
                </span>
            </div>

            {{-- Isi Card --}}
            <div class="p-5">

                <h4 class="font-bold text-lg text-gray-900">Pondok Siti Hajar</h4>

                <p class="mt-1 text-sm text-gray-700 flex items-center gap-1">
                    <span>📍</span> Jl. H. Agus Salim no.62 Tirosompa
                </p>

                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Tersedia 4 area peristirahatan dengan lantai kayu asli, gazebo  
                    florest beton ketahanan keamanan yang memberikan kenyamanan bagi 
                    tamu lokal maupun domestik.
                </p>

                {{-- Ketersediaan --}}
                <div class="mt-4 text-xs text-gray-600">
                    Tersedia: <strong>Harian | Bulanan | Tahunan</strong>
                </div>

                {{-- Tombol --}}
                <div class="mt-4">
                    <a href="/cabang/parepare/kost" 
                       class="block text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-md font-semibold">
                        Detail
                    </a>
                </div>

            </div>
        </div>

        {{-- CARD 2 --}}
        <div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100">
            
            <div class="relative h-48 w-full">
                <img src="{{ asset('images/villa.jpg') }}" 
                     class="w-full h-full object-cover" alt="Penginapan">

                <span class="absolute top-2 left-2 bg-white text-gray-800 text-xs px-3 py-1 rounded-full shadow">
                    ● PAREPARE
                </span>
            </div>

            <div class="p-5">

                <h4 class="font-bold text-lg text-gray-900">The Green Villa</h4>

                <p class="mt-1 text-sm text-gray-700 flex items-center gap-1">
                    <span>📍</span> Bilalang Parepare
                </p>

                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Memiliki sarana dan prasarana lengkap yang asri, memberikan kenyamanan 
                    serta pelayanan terbaik bagi tamu yang beristirahat maupun keperluan perjalanan.
                </p>

                <div class="mt-4 text-xs text-gray-600">
                    Tersedia: <strong>Harian</strong>
                </div>

                <div class="mt-4">
                    <a href="/cabang/parepare/villa" 
                       class="block text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-md font-semibold">
                        Detail
                    </a>
                </div>

            </div>
        </div>

        {{-- CARD 3 --}}
        <div class="bg-white rounded-xl overflow-hidden shadow-lg border border-gray-100">
            
            <div class="relative h-48 w-full">
                <img src="{{ asset('images/pondok.jpg') }}" 
                     class="w-full h-full object-cover" alt="Penginapan">

                <span class="absolute top-2 left-2 bg-white text-gray-800 text-xs px-3 py-1 rounded-full shadow">
                    ● PANGKEP
                </span>
            </div>

            <div class="p-5">

                <h4 class="font-bold text-lg text-gray-900">Pondok Siti Hajar</h4>

                <p class="mt-1 text-sm text-gray-700 flex items-center gap-1">
                    <span>📍</span> Jl. Matahari Dalam, Lr. Anggrek Pangkep
                </p>

                <p class="mt-3 text-sm text-gray-600 leading-relaxed">
                    Tersedia 4 kamar bersih dan nyaman dekat area perbelanjaan,  
                    memberikan kemudahan akses dan perjalanan untuk tamu lokal.
                </p>

                <div class="mt-4 text-xs text-gray-600">
                    Tersedia: <strong>Harian | Bulanan | Tahunan</strong>
                </div>

                <div class="mt-4">
                    <a href="/cabang/pangkep/kost" 
                       class="block text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded-md font-semibold">
                        Detail
                    </a>
                </div>

            </div>
        </div>

    </div>
</section>


{{-- SECTION PONDOK SITI HAJAR --}}
<section class="py-20 container mx-auto px-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <img src="{{ asset('images/background.jpg') }}" class="rounded-lg shadow">

        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold font-serif text-green-700">Pondok Siti Hajar</h3>

            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.
            </p>

            <a href="/cabang/parepare/kost" 
               class="mt-6 inline-block px-5 py-2 border border-green-700 text-green-700 rounded">
                Selengkapnya
            </a>
        </div>

    </div>
</section>


{{-- SECTION THE GREEN VILLA --}}
<section class="py-20 bg-gray-50 container mx-auto px-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold font-serif text-green-700">The Green Villa</h3>

            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.
            </p>

            <a href="/cabang/parepare/villa" 
               class="mt-6 inline-block px-5 py-2 border border-green-700 text-green-700 rounded">
                Selengkapnya
            </a>
        </div>

        <img src="{{ asset('images/villa.jpg') }}" class="rounded-lg shadow">

    </div>
</section>


{{-- SECTION PONDOK SATU --}}
<section class="py-20 container mx-auto px-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

        <img src="{{ asset('images/pondok.jpg') }}" class="rounded-lg shadow">

        <div class="flex flex-col justify-center">
            <h3 class="text-2xl font-bold font-serif text-green-700">Pondok Satu</h3>

            <p class="mt-3">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor.
            </p>

            <a href="/cabang/pangkep/kost" 
               class="mt-6 inline-block px-5 py-2 border border-green-700 text-green-700 rounded">
                Selengkapnya
            </a>
        </div>

    </div>
</section>
@endsection
