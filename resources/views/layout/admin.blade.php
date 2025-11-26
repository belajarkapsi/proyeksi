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
<body class="bg-gray-100">

    <x-navbar-admin></x-navbar-admin>

    <!-- Sidebar -->
    <aside id="sidebar" class="bg-gray-900 text-gray-300 w-64 fixed top-0 left-0 bottom-0 z-60 transform -translate-x-full transition-transform duration-300 ease-in-out">
        <!-- PERBAIKAN: Menambahkan pt-14 di sini -->
        <div class="flex flex-col h-full overflow-y-auto">
            <div class="py-6 px-4">
                {{-- ... CORE ... --}}
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Core</div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg mb-2 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-green-600 text-white shadow' : 'hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="font-medium">Dashboard</span>
                </a>
                {{-- ... MANAJEMEN ... --}}
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 mt-8">Manajemen</div>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition-all duration-200 mb-1"><i class="fas fa-bed w-6 text-center"></i><span class="font-medium">Data Kamar</span></a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition-all duration-200 mb-1"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Penyewa</span></a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition-all duration-200 mb-1"><i class="fas fa-file-invoice-dollar w-6 text-center"></i><span class="font-medium">Transaksi</span></a>
                {{-- ... LAPORAN ... --}}
                <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4 mt-8">Laporan</div>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 hover:text-white transition-all duration-200 mb-1"><i class="fas fa-chart-line w-6 text-center"></i><span class="font-medium">Keuangan</span></a>
            </div>

            <!-- Profil Pengguna -->
            <div class="mt-auto bg-gray-800 p-4 border-t border-gray-700">
                <div class="flex items-center gap-3">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->nama_lengkap ?? 'A' }}&background=10b981&color=fff" class="w-8 h-8 rounded-full">
                    <div class="overflow-hidden">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->nama_lengkap ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400">Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Overlay untuk mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-30 hidden"></div>

    <!-- Konten Utama -->
    <main id="admin-main" class="pt-14 transition-all duration-300 ease-in-out">
        <div class="p-6 md:p-8">
            <div class="content max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>
        <x-footer></x-footer>
    </main>

    @stack('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('admin-main');
    const navbarContent = document.getElementById('navbar-content-wrapper');
    const overlay = document.getElementById('sidebar-overlay');
    const hamburgerBtn = document.getElementById('admin-hamburger');

    if (!sidebar || !mainContent || !navbarContent || !hamburgerBtn) return;

    // helper: set left margin for desktop
    const setDesktopMargin = (open) => {
        if (window.innerWidth >= 768) {
            // 16rem = 256px -> sama dengan Tailwind 'ml-64'
            mainContent.style.marginLeft = open ? '16rem' : '';
            navbarContent.style.marginLeft = open ? '16rem' : '';
        } else {
            // mobile: main & navbar tidak diberi margin
            mainContent.style.marginLeft = '';
            navbarContent.style.marginLeft = '';
        }
    };

    const openSidebar = () => {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        setDesktopMargin(true);

        if (window.innerWidth < 768 && overlay) {
            overlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    };

    const closeSidebar = () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        setDesktopMargin(false);

        if (window.innerWidth < 768 && overlay) {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    // Pastikan sidebar dalam keadaan "tertutup" saat load
    // (jaga konsistensi jika markup lain pernah me-reset class)
    if (window.innerWidth >= 768) {
        openSidebar();
    } else {
        closeSidebar();
    }

    // Toggle saat klik hamburger
    hamburgerBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        if (sidebar.classList.contains('-translate-x-full')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    });

    // tutup bila klik overlay (mobile)
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }

    // Jika user meresize window, sinkronkan state margin supaya tidak macet
    window.addEventListener('resize', () => {
        // bila sidebar sedang terbuka (cek class translate-x-0), apply margin sesuai width
        const isOpen = sidebar.classList.contains('translate-x-0') && !sidebar.classList.contains('-translate-x-full');
        setDesktopMargin(isOpen);

        // jika diubah ke desktop, sembunyikan overlay jika ada
        if (window.innerWidth >= 768 && overlay) {
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // Optional: klik di luar sidebar menutup (hanya di mobile agar UX nyaman)
    document.addEventListener('click', (e) => {
        if (window.innerWidth < 768) {
            const target = e.target;
            if (!sidebar.contains(target) && !hamburgerBtn.contains(target) && !overlay.classList.contains('hidden')) {
                closeSidebar();
            }
        }
    });
});
</script>


</body>
</html>
