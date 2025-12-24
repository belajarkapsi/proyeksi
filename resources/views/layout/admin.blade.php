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

    {{-- Navbar --}}
    <x-navbar-admin></x-navbar-admin>

    {{-- Sidebar --}}
    <x-sidebar-admin></x-sidebar-admin>

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
