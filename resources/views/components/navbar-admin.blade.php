<nav class="bg-white border-b border-gray-200 shadow-sm fixed top-0 left-0 right-0 z-50">
    <div class="max-w-full mx-auto px-4 lg:px-6">
        <div id="navbar-content-wrapper" class="flex items-center justify-between h-16 transition-all duration-300 ease-in-out">

            {{-- LEFT: Hamburger --}}
            <div id="navbar-left" class="flex items-center">
                <button id="admin-hamburger" type="button" class="p-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div class="hidden md:block"></div>

            {{-- RIGHT: Icons + User Dropdown --}}
            <div id="navbar-right" class="flex items-center gap-4">
                {{-- Notifikasi --}}
                <button class="p-1 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-300">
                    <svg class="w-6 h-6 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.365V3m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.365ZM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 0 0 4.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92Z"/></svg>
                </button>

                <div class="hidden sm:block h-7 border-l border-gray-700 mr-2"></div>

                {{-- USER MENU CONTAINER (Relative untuk posisi dropdown) --}}
                <div class="relative">
                    {{-- Tombol Profil --}}
                    <button id="user-menu-button" type="button" class="inline-flex items-center gap-3 bg-transparent border-0 px-0 py-0 focus:outline-none hover:bg-gray-100 hover:rounded-full">
                        <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_lengkap) . '&background=10b981&color=fff&size=128' }}" alt="{{ Auth::user()->nama_lengkap ?? 'User' }}" class="size-10 rounded-full object-cover border-2 border-gray-200">

                        <div class="hidden sm:flex flex-col items-start leading-tight text-left">
                            <span class="text-sm font-medium text-gray-700 truncate">{{ Auth::user()->nama_lengkap ?? 'Nama User' }}</span>
                            <span class="text-xs text-green-600">Pemilik</span>
                        </div>

                        <svg id="user-menu-arrow" class="w-5 h-5 text-gray-500 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- DROPDOWN MENU (Hidden by default) --}}
                    {{-- Absolute right-0 top-full mt-2 memastikan posisi di bawah tombol kanan --}}
                    <div id="user-menu-dropdown" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-100 z-50">
                        <div class="py-1">
                            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-800 hover:bg-gray-100 hover:text-green-700 focus:outline-none cursor-pointer">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                <span>Profil Saya</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center gap-3 px-4 py-3 text-gray-800 hover:bg-gray-100 hover:text-red-600 focus:outline-none cursor-pointer">
                                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2"/></svg>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

{{-- SCRIPT VANILLA JS (Simpan di file layout atau di bawah nav ini) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu-dropdown');
    const arrowIcon = document.getElementById('user-menu-arrow');

    if (userBtn && userMenu) {
        // Toggle saat tombol diklik
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            if(arrowIcon) arrowIcon.classList.toggle('rotate-180');
        });

        // Tutup jika klik di luar
        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
                if(arrowIcon) arrowIcon.classList.remove('rotate-180');
            }
        });
    }
});
</script>
