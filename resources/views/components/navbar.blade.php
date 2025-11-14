<nav class="bg-white sticky top-0 z-50">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        
        <!-- KIRI: Logo & Nama Aplikasi -->
        <a href="/" class="flex items-center space-x-3">
            <img src="{{ asset('images/logo.png') }}" class="h-9" alt="SIPESTAR Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap">SIPESTAR</span>
        </a>

        <!-- TENGAH: Link Navigasi (yang akan disembunyikan di mobile) -->
        <div class="hidden lg:block">
            <div class="bg-white shadow-md rounded-full px-3 py-2">
                <ul class="flex items-center space-x-4">
                    <li>
                        <a href="#" class="text-gray-800 hover:text-green-700 font-medium px-3 py-2 rounded-full transition-colors duration-300">Home</a>
                    </li>
                    <li>
                        <a href="/lokasi" class="text-gray-800 hover:text-green-700 font-medium px-3 py-2 rounded-full transition-colors duration-300">Lokasi</a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-800 hover:text-green-700 font-medium px-3 py-2 rounded-full transition-colors duration-300">Pusat Bantuan</a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- KANAN: Tombol Login & Tombol Hamburger (Mobile) -->
        <div class="flex items-center lg:order-2 space-x-2">
            <a href="{{ route('login') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">Login</a>
            
            {{-- Tombol Hamburger hanya muncul di layar kecil (di bawah lg) --}}
            <button data-collapse-toggle="navbar-links" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-links" aria-expanded="false">
                <span class="sr-only">Buka menu utama</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu Dropdown untuk Mobile -->
    <div class="hidden lg:hidden" id="navbar-mobile-menu">
        <ul class="flex flex-col font-medium p-4">
            <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Home</a>
            </li>
            <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Location</a>
            </li>
            <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Help Center</a>
            </li>
        </ul>
    </div>
</nav>