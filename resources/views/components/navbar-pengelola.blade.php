<nav class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-30 drop-shadow-xl">
    <div class="px-4 sm:px-4 lg:px-6">
        <div class="relative flex items-center justify-between h-16 transition-all duration-300 ease-in-out">
            <div class="flex items-center shrink-0">
                <a href="{{ route('pengelola.dashboard') }}">
                    <img class="h-8 w-auto" src="{{ asset('images/logo.png') }}" alt="Pondok Siti Hajar">
                </a>
            </div>

            <div class="hidden md:block absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                <div class="flex justify-center items-center space-x-1 lg:space-x-4">
                    <a href="{{ route('pengelola.dashboard') }}" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Home</a>

                    <a href="{{ route('pengelola.penyewa.index') }}" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Penyewa</a>

                    <a href="{{ route('pengelola.kamar.index') }}" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Layanan</a>

                    <a href="{{ route('pengelola.pemesanan.index') }}" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Daftar Pesanan</a>

                    <a href="#" class="text-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-semibold border-transparent hover:bg-green-400 transition-colors duration-300">Laporan</a>
                </div>
            </div>

            <div>
                <div class="hidden md:flex items-center space-x-4">
                    <button type="button" class="p-1 rounded-full text-gray-500 hover:text-gray-800 focus:outline-none hover:bg-gray-100 transition">
                        <span class="sr-only">View notifications</span>
                        <i class="fas fa-bell"></i>
                    </button>

                    <div class="relative dropdown">
                        <button type="button" class="dropdown-toggle max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 p-1 pr-2 hover:bg-gray-50 transition">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full object-cover border border-gray-200" src="https://i.pravatar.cc/150?u=john" alt="User Avatar">
                            <div class="ml-2 text-left hidden lg:block">
                                <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->nama_lengkap ?? 'Pengelola' }}</p>
                                <p class="text-xs text-gray-500">Pengelola</p>
                            </div>
                            <i class="dropdown-icon fas fa-chevron-down ml-3 text-xs text-gray-500 hidden lg:block transition-transform duration-300 transform"></i>
                        </button>

                        <div class="dropdown-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5
                                    transition-all duration-300 ease-in-out transform
                                    opacity-0 invisible scale-95 -translate-y-2">
                            <div class="py-1">
                                <a href="{{ route('pengelola.profile.edit') }}" class="flex justify-start gap-1 px-2 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                    </svg>
                                    Profil Saya
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex justify-start w-full text-left gap-1 px-2 py-2 text-sm text-red-600 hover:bg-red-50 hover:cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>
                                    Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex md:hidden">
                    <button type="button" id="hamburger-button" class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars fa-lg transition-transform duration-300" id="hamburger-icon"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="md:hidden overflow-hidden transition-all duration-300 ease-in-out max-h-0 opacity-0" id="mobile-menu">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t border-gray-100 shadow-inner">
            <a href="#" class="bg-green-50 text-green-700 block px-3 py-2 rounded-md text-base font-medium">Home</a>
            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Data Kamar</a>
            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Pesanan</a>
            <a href="#" class="text-gray-700 hover:bg-gray-50 hover:text-gray-900 block px-3 py-2 rounded-md text-base font-medium">Laporan</a>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center px-5">
                <div class="shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover border border-white shadow" src="https://i.pravatar.cc/150?u=john" alt="">
                </div>
                <div class="ml-3">
                    <div class="text-base font-medium leading-none text-gray-800">{{ Auth::user()->nama_lengkap ?? 'John Doe' }}</div>
                    <div class="text-sm font-medium leading-none text-gray-500 mt-1">Pengelola</div>
                </div>
            </div>
            <div class="mt-3 px-2 space-y-1">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-white hover:shadow-sm">Profil Saya</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50">Logout</button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- 1. Logika untuk Menu Mobile (Slide Down Effect) ---
    const hamburgerBtn = document.getElementById('hamburger-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');

    if (hamburgerBtn && mobileMenu) {
        hamburgerBtn.addEventListener('click', () => {
            // Cek apakah menu sedang tertutup berdasarkan max-height
            const isClosed = mobileMenu.style.maxHeight === '0px' || mobileMenu.classList.contains('max-h-0');

            if (isClosed) {
                mobileMenu.classList.remove('max-h-0', 'opacity-0');
                mobileMenu.style.maxHeight = mobileMenu.scrollHeight + "px"; // Set tinggi sesuai konten
                hamburgerIcon.classList.remove('fa-bars');
                hamburgerIcon.classList.add('fa-times'); // Ubah icon jadi X
            } else {
                mobileMenu.classList.add('max-h-0', 'opacity-0');
                mobileMenu.style.maxHeight = null;
                hamburgerIcon.classList.remove('fa-times');
                hamburgerIcon.classList.add('fa-bars');
            }
        });
    }

    // --- 2. Logika untuk Dropdown Smooth ---
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');

    // Fungsi menutup dropdown
    const closeDropdown = (menu, icon) => {
        // Tambahkan class penutup (hidden state)
        menu.classList.add('opacity-0', 'invisible', 'scale-95', '-translate-y-2');
        menu.classList.remove('opacity-100', 'visible', 'scale-100', 'translate-y-0');

        // Reset rotasi icon
        if(icon) {
            icon.classList.remove('rotate-180');
        }
    };

    // Fungsi membuka dropdown
    const openDropdown = (menu, icon) => {
        // Hapus class penutup, tambah class pembuka
        menu.classList.remove('opacity-0', 'invisible', 'scale-95', '-translate-y-2');
        menu.classList.add('opacity-100', 'visible', 'scale-100', 'translate-y-0');

        // Rotasi icon
        if(icon) {
            icon.classList.add('rotate-180');
        }
    };

    // Tutup semua dropdown lain saat satu diklik
    const closeAllDropdowns = () => {
        document.querySelectorAll('.dropdown').forEach(container => {
            const menu = container.querySelector('.dropdown-menu');
            const icon = container.querySelector('.dropdown-icon');
            if(menu) closeDropdown(menu, icon);
        });
    };

    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function (event) {
            event.stopPropagation();

            const parent = this.closest('.dropdown');
            const menu = parent.querySelector('.dropdown-menu');
            const icon = parent.querySelector('.dropdown-icon');

            // Cek status saat ini (apakah sedang invisble?)
            const isClosed = menu.classList.contains('invisible');

            // Tutup yang lain dulu
            closeAllDropdowns();

            // Jika tadi tertutup, maka sekarang buka
            if (isClosed) {
                openDropdown(menu, icon);
            }
        });
    });

    // Menutup dropdown jika klik di luar area
    window.addEventListener('click', function (event) {
        if (!event.target.closest('.dropdown')) {
            closeAllDropdowns();
        }
    });
});
</script>
