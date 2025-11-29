<footer class="relative bg-green-600 text-white pt-10 pb-6 border-t border-green-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Grid Layout: 1 Kolom di Mobile, 12 Kolom di Desktop -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-8 items-start">

            <!-- BAGIAN KIRI: Logo & Branding (Mengambil 6 kolom / 50% lebar) -->
            <div class="md:col-span-6 space-y-4">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" class="w-12 h-12 object-contain bg-white rounded-full p-1 shadow-sm" alt="SIPESTAR Logo">
                    <div>
                        <h2 class="text-2xl font-bold tracking-tight">SIPESTAR</h2>
                        <p class="text-green-100 text-xs font-medium tracking-wider uppercase">Pondok Siti Hajar</p>
                    </div>
                </a>
                <p class="text-white/90 text-sm leading-relaxed max-w-md">
                    Platform pengelolaan hunian terpercaya di Sulawesi Selatan. Kenyamanan Anda adalah prioritas kami.
                </p>

                <!-- Social Media Kecil -->
                <div class="flex gap-3 pt-1">
                    <a href="#" class="p-2 bg-green-700 rounded-full hover:bg-white hover:text-green-600 transition-all duration-300" aria-label="Facebook">
                        <svg class="size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="#" class="p-2 bg-green-700 rounded-full hover:bg-white hover:text-green-600 transition-all duration-300" aria-label="Instagram">
                        <svg class="size-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/></svg>
                    </a>
                </div>
            </div>

            <!-- BAGIAN KANAN: Kontak & Lokasi (Sejajar) -->

            <!-- Kontak (Mengambil 3 kolom) -->
            <div class="md:col-span-3 space-y-4">
                <h3 class="text-lg font-bold border-b-2 border-green-400 pb-1 inline-block">Hubungi Kami</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-center gap-2 group">
                        <svg class="w-5 h-5 text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="group-hover:text-green-100 transition-colors">@Pondok Siti Hajar</span>
                    </li>
                    <li class="flex items-center gap-2 group">
                        <svg class="w-5 h-5 text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="group-hover:text-green-100 transition-colors">+62-8532-3213</span>
                    </li>
                </ul>
            </div>

            <!-- Lokasi (Mengambil 3 kolom) -->
            <div class="md:col-span-3 space-y-4">
                <h3 class="text-lg font-bold border-b-2 border-green-400 pb-1 inline-block">Cabang Kami</h3>
                <ul class="space-y-3 text-sm">
                    <li>
                        <a href="/cabang" class="flex items-center gap-2 hover:text-green-200 hover:translate-x-1 transition-all duration-200">
                            <svg class="w-5 h-5 text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Pangkep
                        </a>
                    </li>
                    <li>
                        <a href="/cabang" class="flex items-center gap-2 hover:text-green-200 hover:translate-x-1 transition-all duration-200">
                            <svg class="w-5 h-5 text-green-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Parepare
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Copyright Simple -->
        <div class="border-t border-green-500 pt-6 flex flex-col md:flex-row justify-between items-center text-sm text-green-100">
            <p>&copy; <span id="current-year"></span> SIPESTAR.</p>
            <p class="flex items-center gap-1 mt-2 md:mt-0">
                Dibuat dengan <span class="text-red-200">❤</span> oleh Tim Kami
            </p>
        </div>
    </div>

    <!-- Back to Top Button (Floating) -->
    <button id="back-to-top" class="fixed bottom-6 right-6 bg-white text-green-600 p-2.5 rounded-full shadow-lg translate-y-20 opacity-0 transition-all duration-500 hover:bg-gray-100 hover:-translate-y-1 focus:outline-none z-40" aria-label="Kembali ke atas">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
    </button>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set Tahun
    document.getElementById('current-year').textContent = new Date().getFullYear();

    // Logika Back to Top
    const backToTopBtn = document.getElementById('back-to-top');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 200) {
            backToTopBtn.classList.remove('translate-y-20', 'opacity-0');
            backToTopBtn.classList.add('translate-y-0', 'opacity-100');
        } else {
            backToTopBtn.classList.add('translate-y-20', 'opacity-0');
            backToTopBtn.classList.remove('translate-y-0', 'opacity-100');
        }
    });

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
});
</script>
