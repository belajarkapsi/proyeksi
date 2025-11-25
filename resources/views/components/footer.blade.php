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
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    <a href="#" class="p-2 bg-green-700 rounded-full hover:bg-white hover:text-green-600 transition-all duration-300" aria-label="Instagram">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465 1.067-.047 1.409-.06 3.809-.06z"/></svg>
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