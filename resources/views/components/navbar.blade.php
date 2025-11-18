<nav class="bg-white sticky top-0 z-50 drop-shadow-xl">
    <div class="max-w-7xl flex flex-wrap items-center justify-between mx-auto p-4">
        
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
                        <a href="{{ route('dashboard') }}" class="text-gray-800 hover:text-green-700 font-medium px-3 py-2 rounded-full transition-colors duration-300">Home</a>
                    </li>
                    <li>
                        <div class="hs-dropdown relative [--placement:bottom] inline-flex">
                            <button id="hs-dropdown-with-dividers" type="button" class="hhs-dropdown-toggle inline-flex items-center gap-x-1 text-gray-800 hover:text-green-700 font-medium px-3 py-2 rounded-full transition-colors duration-300 focus:outline-none hover:cursor-pointer" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                Lokasi
                                <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-dividers">
                                <div class="p-1 space-y-0.5">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100" href="/cabang/parepare/kost">
                                        Kost Parepare
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100" href="/cabang/parepare/villa">
                                        Villa Parepare
                                    </a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100" href="/cabang/pangkep/kost">
                                        Kost Pangkep
                                    </a>
                                </div>
                            </div>
                        </div>
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
                <a href="/cabang" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Lokasi</a>
            </li>
            <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Pusat Bantuan</a>
            </li>
        </ul>
    </div>
</nav>