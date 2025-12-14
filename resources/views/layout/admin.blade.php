<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <x-navbar-admin></x-navbar-admin>

    <aside id="sidebar" class="bg-green-700 w-64 fixed top-0 left-0 bottom-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col h-full shadow-xl">

        <div class="flex-1 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-green-800 [&::-webkit-scrollbar-thumb]:bg-green-500 hover:[&::-webkit-scrollbar-thumb]:bg-green-400">
            <div class="py-6 px-4">

                {{-- LOGO --}}
                <div class="tracking-wider mb-10 flex items-center gap-3 justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="size-12 object-contain">
                    <span class="text-xl font-bold text-white uppercase whitespace-nowrap">ADMIN PAGE</span>
                </div>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 text-white hover:text-gray-800 hover:bg-white hover:rounded-lg">
                    <i class="fas fa-tachometer-alt w-6 mt-1 text-center"></i>
                    <span class="font-bold text-xl">Dashboard</span>
                </a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- ... DATA PENGGUNA ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Data Pengguna</div>
                <a href="{{ route('penyewa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Penyewa</span></a>
                <a href="{{ route('pengelola.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Pengelola</span></a>

                {{-- Pemisah --}}
                <div class="block border-b border-white"></div>

                {{-- ... ITEM MENU LAINNYA (Silakan copas menu Anda di sini) ... --}}
                {{-- Contoh Dropdown Parepare dengan Background Gelap --}}
                {{-- ... MANAJEMEN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Manajemen Cabang</div>

                {{-- Parepare --}}
                <div class="sidebar-dropdown w-full mb-1">
                    <button type="button"
                            class="dropdown-toggle flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-lg bg-transparent focus:outline-none text-white hover:text-gray-800 hover:bg-white transition-all duration-200"
                            aria-expanded="false" aria-controls="menu-parepare">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-map-location w-6 text-center"></i>
                                <span class="font-medium">Parepare</span>
                            </div>
                            {{-- Sebelum diklik --}}
                            <div class="flex items-center">
                                <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                {{-- Setelah diklik --}}
                                <svg class="hidden size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/></svg>
                            </div>
                        </button>
                    {{-- Background Dropdown Gelap --}}
                    <div id="menu-parepare" class="dropdown-menu overflow-hidden max-h-0 transition-[max-height] duration-300 ease-in-out bg-green-800 rounded-lg mt-1 mx-1">
                        <div class="py-2 space-y-1">
                            <a href="{{ route('admin.cabangkamar.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fas fa-bed text-sm text-center"></i><span class="font-medium">Data Kamar</span></a>
                            <a href="{{ route('admin.cabanglayanan-villa.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fa-solid fa-bell-concierge text-sm text-center"></i><span class="font-medium">Layanan Villa</span></a>
                            <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fa-solid fa-circle-info text-sm text-center"></i><span class="font-medium">Informasi Cabang</span></a>
                        </div>
                    </div>
                </div>

                {{-- Pangkep --}}
                <div class="sidebar-dropdown w-full mb-1">
                    <button type="button"
                            class="dropdown-toggle flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-lg bg-transparent focus:outline-none text-white hover:text-gray-800 hover:bg-white transition-all duration-200"
                            aria-expanded="false" aria-controls="menu-parepare">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-map-location w-6 text-center"></i>
                                <span class="font-medium">Pangkep</span>
                            </div>
                            {{-- Sebelum diklik --}}
                            <div class="flex items-center">
                                <svg class="size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                {{-- Setelah diklik --}}
                                <svg class="hidden size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/></svg>
                            </div>
                        </button>
                    {{-- Background Dropdown Gelap --}}
                    <div id="menu-parepare" class="dropdown-menu overflow-hidden max-h-0 transition-[max-height] duration-300 ease-in-out bg-green-800 rounded-lg mt-1 mx-1">
                        <div class="py-2 space-y-1">
                            <a href="{{ route('admin.cabangkamar.index', ['lokasi' => 'pangkep']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fas fa-bed text-sm text-center"></i><span class="font-medium">Data Kamar</span></a>
                            <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => 'pangkep']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fa-solid fa-circle-info text-sm text-center"></i><span class="font-medium">Informasi Cabang</span></a>
                        </div>
                    </div>
                </div>

                {{-- Pemesanan --}}
                <a href="{{ route('daftar-pemesanan.index') }}"
   class="
        flex items-center gap-3 px-3 py-2.5 rounded-lg
        text-white hover:text-gray-800 hover:bg-white
        transition-all duration-200 mb-1
        {{ request()->routeIs('admin.daftar-pemesanan.*') ? 'bg-white text-gray-800 font-semibold' : '' }}
    ">
    <i class="fa-solid fa-clipboard-list w-6 text-center"></i>
    <span class="font-medium">Daftar Pemesanan</span>
</a>

                {{-- Pembayaran --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-file-invoice-dollar w-6 text-center"></i><span class="font-medium">Transaksi</span></a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- ... LAPORAN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Laporan</div>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fa-solid fa-chart-area w-6 text-center"></i><span class="font-medium">Hunian</span></a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-chart-line w-6 text-center"></i><span class="font-medium">Keuangan</span></a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- Setting --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 mt-2 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1">
                    <i class="fa-solid fa-gear w-6 text-center"></i><span class="font-medium">Pengaturan</span>
                </a>

            </div>
        </div>

        {{-- Footer Sidebar --}}
        <div class="bg-green-800 py-5 px-4 border-t border-green-600 shrink-0">
            <div class="flex items-center gap-3 w-full">
                <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) . '?t=' . time() : 'https://ui-avatars.com/api/?name=' . (Auth::user()->nama_lengkap ?? 'A') . '&background=10b981&color=fff' }}"
                    class="size-10 rounded-full border-2 border-green-400 shrink-0 object-cover">

                <div class="overflow-hidden flex-1 min-w-0">
                    <p class="text-xs text-gray-400">Anda login sebagai:</p>
                    <p class="text-sm font-bold text-white truncate" title="{{ Auth::user()->nama_lengkap }}">
                        {{ Auth::user()->nama_lengkap ?? 'Admin' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="text-white hover:text-red-600 transition-colors p-1" title="Logout">
                        <i class="fa-solid fa-right-from-bracket text-2xl"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden transition-opacity md:hidden"></div>

    <main id="admin-main" class="pt-14 transition-all duration-300 ease-in-out">
        <div class="p-6 md:p-8 lg:p-12">
            <div class="content w-full max-w-none mx-auto">
                @yield('content')
            </div>
        </div>
        <x-footer></x-footer>
    </main>

    @include('sweetalert::alert')
    @stack('scripts')

    {{-- SCRIPT PENGATUR LAYOUT & DROPDOWN --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. INISIALISASI VARIABEL ---
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('admin-main');
        const navbarLeft = document.getElementById('navbar-left');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburgerBtn = document.getElementById('admin-hamburger');

        if (!sidebar || !mainContent || !hamburgerBtn) return;

        // --- 2. FUNGSI LAYOUT (Desktop Push / Mobile Overlay) ---
        const updateLayout = () => {
            const isDesktop = window.innerWidth >= 768;
            const isSidebarOpen = sidebar.classList.contains('translate-x-0');

            if (isDesktop) {
                // Desktop: Push Layout
                if (overlay) overlay.classList.add('hidden');
                document.body.style.overflow = '';

                if (isSidebarOpen) {
                    mainContent.style.marginLeft = '16rem';
                    mainContent.style.width = 'calc(100% - 16rem)';
                    if(navbarLeft) navbarLeft.style.transform = 'translateX(16rem)';
                } else {
                    mainContent.style.marginLeft = '0';
                    mainContent.style.width = '100%';
                    if(navbarLeft) navbarLeft.style.transform = 'translateX(0)';
                }
            } else {
                // Mobile: Overlay Layout
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
                if(navbarLeft) navbarLeft.style.transform = 'none'; // Hamburger diam

                if (isSidebarOpen) {
                    if (overlay) overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    if (overlay) overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            }
        };

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            updateLayout();
        };

        const closeSidebar = () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            updateLayout();
        };

        // --- 3. EVENT LISTENERS SIDEBAR ---
        // Cek layar saat load
        if (window.innerWidth >= 768) openSidebar(); else closeSidebar();

        // Hamburger click
        hamburgerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.contains('-translate-x-full') ? openSidebar() : closeSidebar();
        });

        // Overlay click
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Resize window
        window.addEventListener('resize', updateLayout);


        // --- 4. LOGIKA DROPDOWN & PANAH (PERBAIKAN UTAMA) ---
        document.querySelectorAll('.sidebar-dropdown').forEach(group => {
            const btn = group.querySelector('.dropdown-toggle');
            const menu = group.querySelector('.dropdown-menu');

            if (!btn || !menu) return;

            btn.addEventListener('click', (e) => {
                e.preventDefault();

                // A. Buka/Tutup Menu (Cek tinggi konten)
                const isOpen = menu.style.maxHeight && menu.style.maxHeight !== '0px';

                if (isOpen) {
                    menu.style.maxHeight = '0px'; // Tutup
                } else {
                    menu.style.maxHeight = menu.scrollHeight + 'px'; // Buka sesuai tinggi konten
                }

                // B. Animasi Panah (Tukar class Hidden pada SVG)
                // Kita cari div pembungkus icon (elemen terakhir di dalam tombol)
                const iconContainer = btn.lastElementChild;
                if (iconContainer) {
                    const svgs = iconContainer.querySelectorAll('svg');
                    // Loop semua svg di dalam tombol dan tukar status hidden-nya
                    svgs.forEach(svg => svg.classList.toggle('hidden'));
                }
            });
        });
    });
    </script>

</body>
</html>
