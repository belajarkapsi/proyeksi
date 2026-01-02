<nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 fixed top-0 left-0 right-0 z-50 transition-all duration-300">
    <div class="max-w-full mx-auto px-4 lg:px-8">
        <div id="navbar-content-wrapper" class="flex items-center justify-between h-16">

            {{-- LEFT: Hamburger & Breadcrumb-like title --}}
            <div id="navbar-left" class="flex items-center gap-4">
                <button id="admin-hamburger" type="button" class="p-2.5 rounded-xl text-gray-500 hover:bg-green-50 hover:text-green-700 transition-all duration-200 focus:outline-none ring-1 ring-transparent active:ring-green-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                {{-- Label halaman (Opsional, menambah kesan profesional) --}}
                <div class="hidden lg:block">
                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">Overview Panel</h1>
                </div>
            </div>

            {{-- RIGHT: Icons + User Dropdown --}}
            <div id="navbar-right" class="flex items-center gap-2 md:gap-5">

                {{-- Notifikasi dengan Badge --}}
                <div class="relative">
                    <button class="p-2.5 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-green-700 transition-all focus:outline-none group">
                        <svg class="w-7 h-7 transition-transform group-hover:rotate-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5.365V3m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175 0 .593 0 1.292-.538 1.292H5.538C5 18 5 17.301 5 16.708c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.365ZM8.733 18c.094.852.306 1.54.944 2.112a3.48 3.48 0 0 0 4.646 0c.638-.572 1.236-1.26 1.33-2.112h-6.92Z"/>
                        </svg>
                        {{-- Dot Indikator --}}
                        <span class="absolute top-2.5 right-2.5 flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500 border-2 border-white"></span>
                        </span>
                    </button>
                </div>

                <div class="hidden sm:block h-8 border-l border-gray-200 mx-1"></div>

                {{-- USER MENU CONTAINER --}}
                <div class="relative">
                    {{-- Tombol Profil --}}
                    <button id="user-menu-button" type="button" class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl hover:bg-gray-50 transition-all duration-200 group focus:outline-none">
                        <div class="relative shrink-0">
                            <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->nama_lengkap) . '&background=10b981&color=fff&size=128' }}"
                                alt="{{ Auth::user()->nama_lengkap ?? 'User' }}"
                                class="size-11 rounded-xl object-cover border-2 border-white shadow-sm ring-1 ring-gray-200">
                        </div>

                        <div class="hidden sm:flex flex-col items-start leading-tight">
                            <span class="text-[15px] font-bold text-gray-800 group-hover:text-green-700 transition-colors">{{ Auth::user()->nama_lengkap ?? 'Nama User' }}</span>
                            <span class="text-xs font-bold text-green-600 uppercase tracking-wider">Pemilik</span>
                        </div>

                        <svg id="user-menu-arrow" class="hidden sm:block w-5 h-5 text-gray-400 transition-transform duration-300 group-hover:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m19 9-7 7-7-7"/>
                        </svg>
                    </button>

                    {{-- DROPDOWN MENU --}}
                    <div id="user-menu-dropdown" class="hidden absolute right-0 top-[110%] w-64 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 overflow-hidden transform origin-top-right transition-all">
                        <div class="p-2">
                            {{-- Info Singkat di HP --}}
                            <div class="sm:hidden px-4 py-3 border-b border-gray-50 mb-1">
                                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->nama_lengkap }}</p>
                                <p class="text-xs text-green-600 font-medium">Pemilik</p>
                            </div>

                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition-all group">
                                <div class="p-2 bg-gray-50 rounded-lg group-hover:bg-white transition-colors">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                                <span>Ke Dashboard Utama</span>
                            </a>

                            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-[15px] font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 rounded-xl transition-all group">
                                <div class="p-2 bg-gray-50 rounded-lg group-hover:bg-white transition-colors">
                                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <span>Profil Saya</span>
                            </a>

                            <div class="my-1 border-t border-gray-50"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-[15px] font-medium text-gray-600 hover:bg-red-50 hover:text-red-600 rounded-xl transition-all group">
                                    <div class="p-2 bg-gray-50 rounded-lg group-hover:bg-white transition-colors">
                                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
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

{{-- SCRIPT VANILLA JS (Tetap Sama, hanya penyesuaian animasi sedikit jika perlu) --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userBtn = document.getElementById('user-menu-button');
    const userMenu = document.getElementById('user-menu-dropdown');
    const arrowIcon = document.getElementById('user-menu-arrow');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('hidden');
            if(arrowIcon) arrowIcon.classList.toggle('rotate-180');
        });

        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.classList.add('hidden');
                if(arrowIcon) arrowIcon.classList.remove('rotate-180');
            }
        });
    }
});
</script>
