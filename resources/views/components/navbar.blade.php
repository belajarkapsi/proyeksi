<style>
    #navbar-mobile-menu {
        transition: max-height 0.3s ease, opacity 0.2s ease;
    }
</style>

<nav class="bg-white sticky top-0 z-50 drop-shadow-xl">
    <div class="w-full flex items-center justify-between px-3 lg:px-6 py-3">

        <a href="/dashboard" class="flex items-center space-x-3 shrink-0  lg:w-56">
            <img src="{{ asset('images/logo.png') }}" class="h-9" alt="SIPESTAR Logo" />
            <span class="self-center text-xl font-semibold whitespace-nowrap text-green-600">SIPESTAR</span>
        </a>

        <div class="hidden lg:flex flex-1 justify-center">
            <div class="bg-white shadow-md rounded-full px-2 py-1">
                <ul class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center min-w-[120px] px-4 py-2 text-gray-800 hover:text-green-700 font-medium rounded-full transition-colors duration-300">Home</a>
                    </li>

                    <li>
                        <div class="hs-dropdown relative [--placement:bottom] inline-flex">
                            <button id="hs-dropdown-lokasi" type="button" class="hs-dropdown-toggle inline-flex items-center  justify-center gap-x-1 min-w-[120px] px-4 py-2 text-gray-800 font-medium hover:text-green-700 rounded-full transition-colors duration-300 focus:outline-none" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                <span>Lokasi</span>
                                <svg class="hs-dropdown-open:rotate-180 size-4 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200 border border-gray-100 z-50" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-lokasi">
                                <div class="p-1 space-y-0.5">
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 hover:text-green-700" href="/cabang/parepare/kost">Kost Parepare</a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 hover:text-green-700" href="/cabang/parepare/villa">Villa Parepare</a>
                                    <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 hover:text-green-700" href="/cabang/pangkep/kost">Kost Pangkep</a>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li>
                        <a href="#" class="inline-flex items-center justify-center min-w-[120px] px-4 py-2 text-gray-800 hover:text-green-700 font-medium rounded-full transition-colors duration-300">Pusat Bantuan</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex items-center lg:order-2 space-x-2 shrink-0 justify-end lg:w-56">

            @if (Auth::guard('pemilik')->check())
                <div class="flex items-center gap-3">
                    <span class="text-red-600 font-bold">
                        Hi, Bos {{ Auth::guard('pemilik')->user()->nama_lengkap }}
                    </span>
                    <a href="{{ route('admin.dashboard') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg">
                    Ke Panel Admin
                    </a>
                    {{-- Form Logout Admin --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            @elseif (Auth::guard('web')->check())
                {{-- TAMPILAN JIKA SUDAH LOGIN (USER DROPDOWN) --}}
                <div class="hs-dropdown relative inline-flex">
                    <button id="hs-dropdown-user" type="button" class="hs-dropdown-toggle py-1.5 px-3 pe-4 inline-flex items-center gap-x-3 text-sm font-medium rounded-full bg-green-600 text-white shadow-md hover:bg-green-700 focus:outline-none transition-all" aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                        {{-- Avatar: Menggunakan UI Avatars (Inisial Nama) agar dinamis --}}
                        <img class="w-8 h-8 rounded-full border-2 border-white/30" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama_lengkap) }}&background=random&color=fff" alt="{{ Auth::user()->nama_lengkap }}">

                        {{-- Nama User --}}
                        <span class="hidden sm:block max-w-[130px] truncate text-left">{{ Auth::user()->username }}</span>

                        <svg class="w-6 h-6  mt-1 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- Isi Menu Dropdown User --}}
                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-lg rounded-lg mt-2 border border-gray-100 z-50" role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-user">
                        <div class="py-3 px-4 border-b border-gray-200 bg-gray-50 rounded-t-lg">
                            <p class="text-sm text-gray-500">Login Sebagai: </p>
                            <p class="text-sm font-medium text-gray-800 truncate">{{ Auth::user()->nama_lengkap }}</p>
                        </div>
                        <div class="p-1 space-y-0.5">
                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 hover:text-green-700 focus:outline-none" href="/profile"> <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                Profil
                            </a>

                            <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 hover:text-green-700 focus:outline-none" href="{{ route('booking.riwayat') }}"> <svg class="shrink-0 size-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M3.22302 14C4.13247 18.008 7.71683 21 12 21c4.9706 0 9-4.0294 9-9 0-4.97056-4.0294-9-9-9-3.72916 0-6.92858 2.26806-8.29409 5.5M7 9H3V5"/></svg>
                                Riwayat Pemesanan
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 hover:text-red-600 focus:outline-none cursor-pointer" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                    Logout
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            @else
            {{-- TAMPILAN JIKA BELUM LOGIN --}}
                <a href="{{ route('login') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">Login</a>
            @endif

            {{-- Tombol Hamburger (Mobile) --}}
            <button data-mobile-menu-toggle="navbar-mobile-menu" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200" aria-controls="navbar-mobile-menu" aria-expanded="false">
                <span class="sr-only">Buka menu utama</span>
                <svg class="icon-hamburger w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>

                <svg class="icon-close w-6 h-6 text-gray-800 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 17.94 6M18 18 6.06 6"/>
                </svg>

            </button>
        </div>
    </div>

    <div class="hidden lg:hidden max-h-0 opacity-0 overflow-hidden" id="navbar-mobile-menu">
        <ul class="flex flex-col font-medium p-4">
            <li>
                <a href="{{ route('dashboard') }}" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Home</a>
            </li>
            <li>
                <a href="/cabang" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Lokasi</a>
            </li>
            <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Pusat Bantuan</a>
            </li>
            @auth
                <li class="border-t mt-2 pt-2">
                    <a href="/profile" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Profil Saya</a>
                    <a href="{{ route('booking.riwayat') }}" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100">Riwayat Pemesanan</a>
                </li>
                <li class="border-t mt-2 pt-2">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="block py-2 px-3 text-red-600 rounded hover:bg-gray-100 cursor-pointer" onclick="event.preventDefault(); this.closest('form').submit();">Logout</a>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</nav>
