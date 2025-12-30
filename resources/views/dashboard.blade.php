@extends('layout.master')

@section('content')

{{-- === HERO SECTION === --}}
<section class="relative h-[500px] md:h-[600px] overflow-hidden bg-gray-50">

    {{-- Background Image dengan Gradient Overlay --}}
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/background.jpg') }}"
        class="w-full h-full object-cover"
        alt="Hero Background">
        {{-- Gradient agar teks terbaca jelas --}}
        <div class="absolute inset-0 bg-linear-to-r from-white via-white/80 to-transparent"></div>
    </div>

    {{-- Main Content --}}
    <div class="container mx-auto px-6 relative z-20 h-full flex items-center">

        {{-- Text Area --}}
        <div class="w-full md:w-1/2 pt-10 md:pt-0 opacity-0 translate-y-10 transition-all duration-1000 ease-out" id="heroText">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold font-serif leading-tight text-gray-900 mb-4">
                Penginapan <span class="text-green-700">Murah</span> <br>
                &amp; <span class="text-green-700">Terjangkau</span>
            </h1>

            <p class="text-base md:text-lg text-gray-600 leading-relaxed mb-8 max-w-md">
                Rasakan kenyamanan menginap dengan fasilitas lengkap, pelayanan ramah, dan harga yang bersahabat. Tempat istirahat terbaik untuk perjalanan Anda.
            </p>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="#area-penginapan"
                    class="bg-green-600 hover:bg-green-700 text-white hover:drop-shadow-lg font-semibold px-8 py-3 rounded-full shadow-lg hover:shadow-green-600/30 transition-all transform hover:-translate-y-1 text-center cursor-pointer">
                    Pesan Sekarang
                </a>
                <a href="#" class="px-8 py-3 rounded-full border-2 border-green-600 text-green-700 font-semibold hover:bg-green-50 transition-all transform hover:-translate-y-1 text-center cursor-pointer">
                    Hubungi Kami
                </a>
            </div>
        </div>

        {{-- Gallery Slider (Kanan) --}}
        <div class="hidden md:block w-1/2 absolute right-0 top-1/2 -translate-y-1/2 pl-10 overflow-hidden">
            <div id="heroGalleryWrapper" class="relative w-full h-[400px] flex items-center">
                {{-- Container Scroll --}}
                <div id="heroGallery" class="flex gap-6 overflow-x-hidden py-4 px-4">

                    {{-- Card 1 --}}
                    <div class="shrink-0 w-[320px] h-[220px] bg-white rounded-2xl shadow-xl overflow-hidden transform transition-transform hover:scale-105 border border-gray-100">
                        <img src="{{ asset('images/background.jpg') }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Card 2 --}}
                    <div class="shrink-0 w-[320px] h-[220px] bg-white rounded-2xl shadow-xl overflow-hidden transform transition-transform hover:scale-105 border border-gray-100">
                        <img src="{{ asset('images/villa.jpg') }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Card 3 --}}
                    <div class="shrink-0 w-[320px] h-[220px] bg-white rounded-2xl shadow-xl overflow-hidden transform transition-transform hover:scale-105 border border-gray-100">
                        <img src="{{ asset('images/pondok.jpg') }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Card 4 (Duplikat untuk efek infinite loop visual) --}}
                    <div class="shrink-0 w-[320px] h-[220px] bg-white rounded-2xl shadow-xl overflow-hidden transform transition-transform hover:scale-105 border border-gray-100">
                        <img src="{{ asset('images/background.jpg') }}" class="w-full h-full object-cover">
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


{{-- === ABOUT SECTION === --}}
<section class="py-20 bg-white overflow-hidden">
    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center gap-12 lg:gap-20">

        {{-- Image Side --}}
        <div class="md:w-1/2 relative scroll-trigger opacity-0 translate-y-10 transition-all duration-700">
            {{-- Decor Blob --}}
            <div class="absolute -top-4 -left-4 w-24 h-24 bg-green-100 rounded-full -z-10"></div>
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-green-50 rounded-full -z-10"></div>

            <img src="{{ asset('images/penginapan.jpg') }}"
            class="w-full rounded-2xl shadow-2xl transform hover:scale-[1.02] transition-transform duration-500 object-cover h-[350px] md:h-[400px]">
        </div>

        {{-- Text Side --}}
        <div class="md:w-1/2 scroll-trigger opacity-0 translate-y-10 transition-all duration-700 delay-100">
            <div class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase mb-4">
                Tentang Kami
            </div>
            <h2 class="text-3xl md:text-4xl font-bold font-serif text-gray-900 mb-6">
                Kenyamanan Anda adalah <br> <span class="text-green-700">Prioritas Kami</span>
            </h2>
            <p class="text-gray-600 leading-relaxed mb-4 text-lg">
                Kami berdedikasi untuk menyediakan akomodasi yang tidak hanya sekadar tempat tidur,
                tetapi juga pengalaman istirahat yang berkualitas.
            </p>
            <p class="text-gray-600 leading-relaxed mb-8">
                Dengan lokasi strategis dan lingkungan yang asri, kami menjadi pilihan utama
                bagi wisatawan maupun pebisnis yang mencari ketenangan di tengah kesibukan.
            </p>

            {{-- Stats Sederhana --}}
            <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-6">
                <div>
                    <h4 class="text-2xl font-bold text-green-700">3+</h4>
                    <p class="text-xs text-gray-500">Cabang</p>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-green-700">24h</h4>
                    <p class="text-xs text-gray-500">Pelayanan</p>
                </div>
                <div>
                    <h4 class="text-2xl font-bold text-green-700">100%</h4>
                    <p class="text-xs text-gray-500">Aman</p>
                </div>
            </div>
        </div>

    </div>
</section>


{{-- === AREA PENGINAPAN SECTION (CLICKABLE CARDS) === --}}
<section id="area-penginapan" class="py-20 bg-gray-50 relative">

    {{-- Background Text Decor --}}
    <div class="absolute top-10 left-0 w-full overflow-hidden leading-none select-none pointer-events-none opacity-5">
        <h2 class="text-[120px] font-black text-gray-900 text-center font-serif whitespace-nowrap">
            PENGINAPAN PILIHAN
        </h2>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-16 scroll-trigger opacity-0 translate-y-10 transition-all duration-700">
            <h2 class="text-3xl md:text-4xl font-bold font-serif text-gray-900">Area Penginapan</h2>
            <div class="w-20 h-1 bg-green-600 mx-auto mt-4 rounded-full"></div>
            <p class="mt-4 text-gray-600 max-w-2xl mx-auto">
                Temukan lokasi terbaik kami yang tersebar di Parepare dan Pangkep,
                siap menyambut kedatangan Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            {{-- CARD 1: PONDOK SITI HAJAR (PAREPARE KOST) --}}
            {{-- Menggunakan tag <a> sebagai wrapper agar seluruh kartu bisa diklik --}}
            <a href="/cabang/parepare/kost" class="group block bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-green-900/10 transition-all duration-300 transform hover:-translate-y-2 scroll-trigger opacity-0 translate-y-10 cursor-pointer">
                {{-- Image Wrapper --}}
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('images/background.jpg') }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    alt="Pondok Siti Hajar">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        PAREPARE
                    </span>
                </div>

                <div class="p-6">
                    <h4 class="font-bold text-xl text-gray-900 font-serif mb-2 group-hover:text-green-700 transition-colors">
                        Pondok Siti Hajar
                    </h4>
                    <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Jl. H. Agus Salim no.62 Tirosompa
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 line-clamp-3">
                        Fasilitas lantai kayu asli, gazebo beton kokoh, dan keamanan 24 jam untuk kenyamanan maksimal.
                    </p>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Bulanan / Harian</span>
                        {{-- Span pengganti link text agar tidak nesting anchor --}}
                        <span class="text-sm font-bold text-green-600 group-hover:text-green-800 flex items-center gap-1">
                            Detail <span class="text-lg transition-transform group-hover:translate-x-1">→</span>
                        </span>
                    </div>
                </div>
            </a>

            {{-- CARD 2: THE GREEN VILLA (PAREPARE VILLA) --}}
            <a href="/cabang/parepare/villa" class="group block bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-green-900/10 transition-all duration-300 transform hover:-translate-y-2 scroll-trigger opacity-0 translate-y-10 delay-100 cursor-pointer">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('images/villa.jpg') }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    alt="The Green Villa">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        PAREPARE
                    </span>
                </div>

                <div class="p-6">
                    <h4 class="font-bold text-xl text-gray-900 font-serif mb-2 group-hover:text-green-700 transition-colors">
                        The Green Villa
                    </h4>
                    <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Bilalang Parepare
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 line-clamp-3">
                        Villa asri dengan sarana lengkap, cocok untuk peristirahatan keluarga maupun perjalanan bisnis.
                    </p>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Harian</span>
                        <span class="text-sm font-bold text-green-600 group-hover:text-green-800 flex items-center gap-1">
                            Detail <span class="text-lg transition-transform group-hover:translate-x-1">→</span>
                        </span>
                    </div>
                </div>
            </a>

            {{-- CARD 3: PONDOK SATU (PANGKEP KOST) --}}
            <a href="/cabang/pangkep/kost" class="group block bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-green-900/10 transition-all duration-300 transform hover:-translate-y-2 scroll-trigger opacity-0 translate-y-10 delay-200 cursor-pointer">
                <div class="relative h-56 overflow-hidden">
                    <img src="{{ asset('images/pondok.jpg') }}"
                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    alt="Pondok Siti Hajar Pangkep">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                    <span class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-green-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                        PANGKEP
                    </span>
                </div>

                <div class="p-6">
                    <h4 class="font-bold text-xl text-gray-900 font-serif mb-2 group-hover:text-green-700 transition-colors">
                        Pondok Satu
                    </h4>
                    <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Jl. Matahari Dalam, Lr. Anggrek
                    </p>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4 line-clamp-3">
                        Kamar bersih dekat area perbelanjaan, akses mudah ke pusat kota Pangkep.
                    </p>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Bulanan / Harian</span>
                        <span class="text-sm font-bold text-green-600 group-hover:text-green-800 flex items-center gap-1">
                            Detail <span class="text-lg transition-transform group-hover:translate-x-1">→</span>
                        </span>
                    </div>
                </div>
            </a>

        </div>
    </div>
</section>


{{-- === DETAIL SECTIONS (Linked Buttons) === --}}
<div class="bg-white py-10">

    {{-- Section 1: KOST PAREPARE --}}
    <section class="container mx-auto px-6 py-12 scroll-trigger opacity-0 translate-y-10 transition-all duration-700">
        <div class="flex flex-col md:flex-row gap-10 items-center">
            <div class="md:w-1/2 h-[300px] md:h-[400px] w-full rounded-2xl overflow-hidden shadow-lg relative group">
                <img src="{{ asset('images/background.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-80"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <span class="text-sm font-light tracking-widest uppercase mb-1 block text-green-300">Parepare</span>
                    <h3 class="text-2xl font-bold font-serif">Pondok Siti Hajar</h3>
                </div>
            </div>
            <div class="md:w-1/2">
                <h3 class="text-3xl font-bold font-serif text-gray-900 mb-4">Kenyamanan Tiada Tara</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Nikmati suasana tenang dengan desain interior klasik yang dipadukan dengan fasilitas modern.
                    Setiap sudut dirancang untuk memberikan ketenangan pikiran Anda setelah seharian beraktivitas.
                </p>
                {{-- Tombol mengarah ke Kost Parepare --}}
                <a href="/cabang/parepare/kost" class="inline-flex items-center justify-center px-6 py-3 border-2 border-green-600 text-green-700 font-semibold rounded-lg hover:bg-green-600 hover:text-white transition-all duration-300 cursor-pointer">
                    Lihat Fasilitas
                </a>
            </div>
        </div>
    </section>

    {{-- Section 2: KOST PANGKEP --}}
    <section class="container mx-auto px-6 py-12 scroll-trigger opacity-0 translate-y-10 transition-all duration-700">
        <div class="flex flex-col md:flex-row gap-10 items-center">
            <div class="md:w-1/2">
                <h3 class="text-3xl font-bold font-serif text-gray-900 mb-4">Lokasi Strategis Pangkep</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Pilihan hunian tepat di jantung kota Pangkep. Akses mudah ke perkantoran dan pusat perbelanjaan,
                    sangat cocok untuk karyawan maupun mahasiswa.
                </p>
                {{-- Tombol mengarah ke Kost Pangkep --}}
                <a href="/cabang/pangkep/kost" class="inline-flex items-center justify-center px-6 py-3 border-2 border-green-600 text-green-700 font-semibold rounded-lg hover:bg-green-600 hover:text-white transition-all duration-300 cursor-pointer">
                    Lihat Fasilitas
                </a>
            </div>
            <div class="md:w-1/2 h-[300px] md:h-[400px] w-full rounded-2xl overflow-hidden shadow-lg relative group">
                <img src="{{ asset('images/pondok.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-80"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <span class="text-sm font-light tracking-widest uppercase mb-1 block text-green-300">Pangkep</span>
                    <h3 class="text-2xl font-bold font-serif">Pondok Satu</h3>
                </div>
            </div>
        </div>
    </section>

    {{-- Section 3: VILLA PAREPARE (Reverse) --}}
    <section class="container mx-auto px-6 py-12 scroll-trigger opacity-0 translate-y-10 transition-all duration-700">
        <div class="flex flex-col md:flex-row-reverse gap-10 items-center">
            <div class="md:w-1/2 h-[300px] md:h-[400px] w-full rounded-2xl overflow-hidden shadow-lg relative group">
                <img src="{{ asset('images/villa.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-80"></div>
                <div class="absolute bottom-6 left-6 text-white">
                    <span class="text-sm font-light tracking-widest uppercase mb-1 block text-green-300">Parepare</span>
                    <h3 class="text-2xl font-bold font-serif">The Green Villa</h3>
                </div>
            </div>
            <div class="md:w-1/2 text-left md:text-right">
                <h3 class="text-3xl font-bold font-serif text-gray-900 mb-4">Nuansa Alam Asri</h3>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Rasakan kesegaran udara dan pemandangan hijau yang memanjakan mata.
                    The Green Villa adalah pilihan tepat bagi Anda yang ingin melarikan diri sejenak dari hiruk pikuk kota.
                </p>
                {{-- Tombol mengarah ke Villa Parepare --}}
                <a href="/cabang/parepare/villa" class="inline-flex items-center justify-center px-6 py-3 border-2 border-green-600 text-green-700 font-semibold rounded-lg hover:bg-green-600 hover:text-white transition-all duration-300 cursor-pointer">
                    Lihat Villa
                </a>
            </div>
        </div>
    </section>

</div>

@endsection

{{-- === JAVASCRIPT === --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    // 1. HERO TEXT ANIMATION ON LOAD
    const heroText = document.getElementById('heroText');
    if(heroText) {
        setTimeout(() => {
            heroText.classList.remove('opacity-0', 'translate-y-10');
        }, 200);
    }

    // 2. HERO GALLERY AUTO SCROLL
    const gallery = document.getElementById('heroGallery');
    if(gallery) {
        const speed = 1; // Kecepatan scroll

        function autoScroll() {
            // Logika infinite scroll visual
            // Jika scroll sudah mendekati akhir (dikurangi clientWidth), reset ke 0
            if(gallery.scrollLeft >= (gallery.scrollWidth - gallery.clientWidth - 2)) {
                gallery.scrollLeft = 0;
            } else {
                gallery.scrollLeft += speed;
            }
        }

        // Jalankan auto scroll
        let scrollInterval = setInterval(autoScroll, 20); // 20ms interval

        // Pause on hover
        const wrapper = document.getElementById('heroGalleryWrapper');
        if(wrapper) {
            wrapper.addEventListener('mouseenter', () => clearInterval(scrollInterval));
            wrapper.addEventListener('mouseleave', () => {
                scrollInterval = setInterval(autoScroll, 20);
            });
        }
    }

    // 3. SCROLL TRIGGER ANIMATION (Fade In Up)
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1 // Trigger saat 10% elemen terlihat
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.remove('opacity-0', 'translate-y-10');
                entry.target.classList.add('opacity-100', 'translate-y-0');
                observer.unobserve(entry.target); // Hanya animasi sekali
            }
        });
    }, observerOptions);

    const scrollElements = document.querySelectorAll('.scroll-trigger');
    scrollElements.forEach((el) => {
        observer.observe(el);
    });

});
</script>
