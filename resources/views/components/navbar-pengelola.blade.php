{{-- resources/views/components/navbar-pengelola.blade.php --}}
<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30 drop-shadow-xl">
    <div class="px-4 sm:px-4 lg:px-6">
        <div class="relative flex items-center justify-between h-16 transition-all duration-300 ease-in-out">
            <!-- KIRI: Logo -->
            <div class="flex items-center shrink-0">
                <a href="#">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Pondok Siti Hajar">
                </a>
            </div>

            <!-- Tengah: Menu -->
            <div class="hidden md:block absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <div class="flex justify-center items-baseline space-x-4">
                    <a href="{{ route('pengelola.dashboard') }}" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Home</a>
                    <a href="{{ route('pengelola.kamar.index') }}" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Data Kamar</a>
                    <!-- Dropdown Pesanan -->
                    <div class="relative dropdown">
                        <button type="button" class="dropdown-toggle flex items-center text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">
                            <span>Pesanan</span>
                            <svg class="ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div class="dropdown-menu hidden absolute z-10 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-white border-transparent hover:bg-green-400 transition-colors duration-300">Daftar Pesanan</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:text-white border-transparent hover:bg-green-400 transition-colors duration-300">Buat Pesanan Baru</a>
                        </div>
                    </div>
                    <a href="#" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Laporan</a>
                </div>
            </div>

            <!-- KANAN: Profil Pengguna -->
            <div>
                <div class="hidden md:flex items-center space-x-4">
                    <button type="button" class="p-1 rounded-full text-gray-500 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="sr-only">Toggle Dark Mode</span>
                        <i class="fas fa-moon"></i>
                    </button>
                    <button type="button" class="p-1 rounded-full text-gray-500 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="sr-only">View notifications</span>
                        <i class="fas fa-bell"></i>
                    </button>
                    <!-- Dropdown Profil -->
                    <div class="relative dropdown">
                        <button type="button" class="dropdown-toggle max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full object-cover" src="https://i.pravatar.cc/150?u=john" alt="User Avatar">
                            <div class="ml-2 text-left hidden lg:block">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama_lengkap ?? 'Pengelola' }}</p>
                                <p class="text-xs text-gray-500">Pengelola</p>
                            </div>
                            <i class="fas fa-chevron-down ml-2 text-xs text-gray-500 hidden lg:block"></i>
                        </button>
                        <div class="dropdown-menu hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tombol Hamburger - Muncul di Mobile -->
                <div class="flex md:hidden">
                    <button type="button" id="hamburger-button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars fa-lg"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Menu Mobile - Tersembunyi secara default -->
    <div class="md:hidden hidden" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <a href="#" class="bg-green-100 text-green-700 block px-3 py-2 rounded-md text-base font-medium">Home</a>
            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Data Kamar</a>
            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Pesanan</a>
            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Laporan</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200">
            <div class="flex items-center px-5">
                <div class="shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover" src="https://i.pravatar.cc/150?u=john" alt="">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-gray-800">{{ Auth::user()->nama_lengkap ?? 'John Doe' }}</div>
                    <div class="text-sm font-medium leading-none text-gray-500">Pengelola</div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-50">Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-gray-50">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

{{-- Vanilla JS untuk interaktivitas --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- Logika untuk Menu Mobile ---
    const hamburgerBtn = document.getElementById('hamburger-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // --- Logika untuk semua Dropdown ---
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    // Fungsi untuk menutup semua dropdown
    const closeAllDropdowns = () => {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.add('hidden');
        });
    };

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function (event) {
            event.stopPropagation(); // Mencegah window click event
            const menu = this.nextElementSibling;

            // Tutup dropdown lain sebelum membuka yang ini
            const isHidden = menu.classList.contains('hidden');
            closeAllDropdowns();

            if (isHidden) {
                menu.classList.remove('hidden');
            }
        });
    });

    // Menutup dropdown jika klik di luar area dropdown
    window.addEventListener('click', function (event) {
        if (!event.target.closest('.dropdown')) {
            closeAllDropdowns();
        }
    });
});
</script>
