<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <x-navbar-admin></x-navbar-admin>

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-green-700 w-64 fixed top-0 left-0 bottom-0 z-60 transform -translate-x-full transition-transform duration-300 ease-in-out">
        <div class="flex flex-col h-full overflow-y-auto">
            <div class="py-6 px-4">
                {{-- ... CORE ... --}}
                <div class="tracking-wider mb-10 flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="size-12 mx-auto my-auto">
                    <span class="text-2xl font-bold text-white uppercase mx-auto my-auto">ADMIN PAGE</span>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-3 transition-all duration-200 text-white hover:text-gray-800 hover:bg-white hover:rounded-lg">
                    <i class="fas fa-tachometer-alt w-6 mt-1 text-center"></i>
                    <span class="font-bold text-xl">Dashboard</span>
                </a>

                {{-- Pemisah --}}
                <div class="block border-b border-white"></div>

                {{-- ... MANAJEMEN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Data Pengguna</div>
                <a href="{{ url('/admin/penyewa') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Penyewa</span></a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Pemilik</span></a>

                {{-- Pemisah --}}
                <div class="block border-b border-white"></div>

                {{-- ... MANAJEMEN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Manajemen Cabang</div>

                {{-- Parepare --}}
                <div class="sidebar-dropdown w-full">
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
                    {{-- DROP-DOWN: flat, no border/shadow/rounded, slightly separated --}}
                    <div id="menu-parepare" class="dropdown-menu w-full overflow-hidden max-h-0 opacity-0 transition-[max-height,opacity] duration-300 ease-in-out" aria-hidden="true">
                        <div class="py-1">
                            <a href="#" class="flex gap-3 items-center px-8 py-2 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fas fa-bed w-6 text-center"></i><span class="font-medium">Data Kamar</span></a>
                            <a href="#" class="flex gap-3 items-center px-8 py-2 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fa-solid fa-bell-concierge w-6 text-center"></i><span class="font-medium">Layanan Villa</span></a>
                        </div>
                    </div>
                </div>
                {{-- Pangkep --}}
                <div class="sidebar-dropdown w-full mt-1">
                    <button type="button"
                            class="dropdown-toggle flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-lg bg-transparent focus:outline-none text-white hover:text-gray-800 hover:bg-white transition-all duration-200"
                            aria-expanded="false" aria-controls="menu-pangkep">
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
                    {{-- DROP-DOWN: flat, no border/shadow/rounded, slightly separated --}}
                    <div id="menu-pangkep" class="dropdown-menu w-full overflow-hidden max-h-0 opacity-0 transition-[max-height,opacity] duration-300 ease-in-out" aria-hidden="true">
                        <div class="py-1">
                            <a href="#" class="flex gap-3 items-center px-8 py-2 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-colors duration-200"><i class="fas fa-bed w-6 text-center"></i><span class="font-medium">Data Kamar</span></a>
                        </div>
                    </div>
                </div>

                {{-- Pemesanan --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fa-solid fa-clipboard-list w-6 text-center"></i><span class="font-medium">Daftar Pemesanan</span></a>

                {{-- Pembayaran --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-file-invoice-dollar w-6 text-center"></i><span class="font-medium">Transaksi</span></a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- ... LAPORAN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Laporan</div>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fa-solid fa-chart-area w-6 text-center"></i><span class="font-medium">Hunian</span></a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fas fa-chart-line w-6 text-center"></i><span class="font-medium">Keuangan</span></a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- Setting --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 mt-2 rounded-lg text-white hover:text-gray-800 hover:bg-white transition-all duration-200 mb-1"><i class="fa-solid fa-gear w-6 text-center text-2xl"></i><span class="font-medium text-lg">Pengaturan</span></a>
            </div>

            <!-- Profil Pengguna -->
            <div class="mt-auto bg-green-800 py-6 px-3 border-t border-gray-600">
                <div class="flex items-center gap-3">
                    <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) . '?t=' . time() : 'https://ui-avatars.com/api/?name=' . (Auth::user()->nama_lengkap ?? 'A') . '&background=10b981&color=fff' }}" class="w-8 h-8 rounded-full">
                    <div class="overflow-hidden">
                        <p class="text-xs text-gray-400">Anda login sebagai:</p>
                        <p class="text-md font-bold text-white truncate">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">
                            <i class="fa-solid fa-arrow-right-from-bracket text-white hover:text-red-600 text-2xl px-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-30 hidden"></div>

    <!-- Konten Utama -->
    <main id="admin-main" class="pt-14 transition-all duration-300 ease-in-out">
        <div class="p-6 md:p-8 lg:p-12">
            <div class="content w-full max-w-none mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    @stack('scripts')

    {{-- Sidebar --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('admin-main');
        const navbarContent = document.getElementById('navbar-content-wrapper');
        const navbarLeft = document.getElementById('navbar-left');
        const navbarRight = document.getElementById('navbar-right');
        const overlay = document.getElementById('sidebar-overlay');
        const hamburgerBtn = document.getElementById('admin-hamburger');

        if (!sidebar || !mainContent || !navbarContent || !hamburgerBtn) return;

        // HELPER: atur layout & transform ketika sidebar open/close
        const setLayoutWhenOpen = (open) => {
            // Untuk desktop/tablet (>=768) kita gunakan marginLeft + width untuk mainContent
            if (window.innerWidth >= 768) {
                if (open) {
                    mainContent.style.marginLeft = '16rem';
                    mainContent.style.width = 'calc(100% - 16rem)';
                } else {
                    mainContent.style.marginLeft = '';
                    mainContent.style.width = '';
                }
            } else {
                // mobile: jangan ubah margin mainContent (agar tidak break), biarkan main tetap full width
                mainContent.style.marginLeft = '';
                mainContent.style.width = '';
            }

            // SELALU translate navbar agar terlihat bergeser (desktop & mobile)
            if (open) {
                if (navbarLeft) navbarLeft.style.transform = 'translateX(16rem)';
            } else {
                if (navbarLeft) navbarLeft.style.transform = '';
            }
        };

        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            setLayoutWhenOpen(true);

            if (window.innerWidth < 768 && overlay) {
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        };

        const closeSidebar = () => {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');
            setLayoutWhenOpen(false);

            if (window.innerWidth < 768 && overlay) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        };

        // state default: desktop terbuka, mobile tertutup
        if (window.innerWidth >= 768) {
            openSidebar();
        } else {
            closeSidebar();
        }

        // Toggle via hamburger
        hamburgerBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sidebar.classList.contains('-translate-x-full')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        });

        if (overlay) overlay.addEventListener('click', closeSidebar);

        // resize -> sinkronisasi
        window.addEventListener('resize', () => {
            const isOpen = sidebar.classList.contains('translate-x-0') && !sidebar.classList.contains('-translate-x-full');
            setLayoutWhenOpen(isOpen);

            if (window.innerWidth >= 768 && overlay) {
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // Optional: klik di luar (mobile) menutup
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 768) {
                const target = e.target;
                if (!sidebar.contains(target) && !hamburgerBtn.contains(target) && overlay && !overlay.classList.contains('hidden')) {
                    closeSidebar();
                }
            }
        });
    });
    </script>

    {{-- Sidebar Dropdown --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // semua dropdown di sidebar
        const sidebarDropdowns = document.querySelectorAll('.sidebar-dropdown');

        sidebarDropdowns.forEach(drop => {
            const btn = drop.querySelector('.dropdown-toggle');
            const menu = drop.querySelector('.dropdown-menu');
            const arrowDown = drop.querySelector('.arrow-down');
            const arrowUp = drop.querySelector('.arrow-up');

            if (!btn || !menu) return;

            // helper open/close
            const open = () => {
                // set maxHeight ke scrollHeight agar animasi slide-down bekerja
                menu.style.maxHeight = menu.scrollHeight + 'px';
                menu.style.opacity = '1';
                menu.setAttribute('aria-hidden', 'false');
                btn.setAttribute('aria-expanded', 'true');

                if (arrowDown) arrowDown.classList.add('hidden');
                if (arrowUp) arrowUp.classList.remove('hidden');
            };

            const close = () => {
                menu.style.maxHeight = '0px';
                menu.style.opacity = '0';
                menu.setAttribute('aria-hidden', 'true');
                btn.setAttribute('aria-expanded', 'false');

                if (arrowDown) arrowDown.classList.remove('hidden');
                if (arrowUp) arrowUp.classList.add('hidden');
            };

            // initial close state (ensure)
            close();

            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // if menu is closed => open, else close
                const isOpen = btn.getAttribute('aria-expanded') === 'true';
                if (isOpen) {
                    close();
                } else {
                    open();
                }
            });

            // Optional: close dropdown when clicking elsewhere (desktop/mobile)
            document.addEventListener('click', (ev) => {
                if (!drop.contains(ev.target)) {
                    // if menu is open, close it
                    if (btn.getAttribute('aria-expanded') === 'true') {
                        close();
                    }
                }
            });

            // Ensure correct maxHeight on window resize (in case content wraps)
            window.addEventListener('resize', () => {
                if (btn.getAttribute('aria-expanded') === 'true') {
                    // recompute height
                    menu.style.maxHeight = menu.scrollHeight + 'px';
                }
            });
        });
    });
    </script>


</body>
</html>
