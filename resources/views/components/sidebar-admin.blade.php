@php
    $dashboard = request()->routeIs('admin.dashboard');
    $penyewa = request()->routeIs('penyewa.*');
    $pengelola = request()->routeIs('pengelola.*');
    $parepare = request()->fullUrlIs('*parepare*');
    $pangkep = request()->fullUrlIs('*pangkep*');

    // Desain Active yang lebih modern dengan bayangan halus
    $active ='bg-white text-green-700 shadow-sm font-semibold rounded-xl';
    $inactive = 'text-green-100 hover:bg-green-600/50 hover:text-white rounded-xl';
@endphp

<div>
    {{-- Sidebar --}}
    <aside id="sidebar" class="bg-green-700 w-72 fixed top-0 left-0 bottom-0 z-50 transform -translate-x-full transition-all duration-300 ease-in-out flex flex-col h-full shadow-2xl border-r border-green-600/30">

        <div class="flex-1 overflow-y-auto px-4 py-6 [&::-webkit-scrollbar]:w-1.5 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-green-500/50 [&::-webkit-scrollbar-thumb]:rounded-full">
            
            {{-- LOGO SECTION --}}
            <div class="px-2 mb-10">
                <div class="flex items-center gap-3 px-2">
                    <div class="p-2 bg-white rounded-xl shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="size-8 object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-lg leading-none tracking-tight">ADMIN</span>
                        <span class="text-green-300 text-[10px] font-bold uppercase tracking-[0.2em]">Dashboard Panel</span>
                    </div>
                </div>
            </div>

            <nav class="space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 transition-all duration-200 group {{ $dashboard ? $active : $inactive }}">
                    <i class="fas fa-tachometer-alt w-5 text-center text-lg {{ $dashboard ? 'text-green-600' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span class="text-sm">Dashboard</span>
                </a>

                {{-- ... DATA PENGGUNA ... --}}
                <div class="pt-6 pb-2 px-4 text-[11px] font-bold text-green-300 uppercase tracking-[0.15em]">Data Pengguna</div>
                
                <a href="{{ route('penyewa.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all duration-200 group mb-1 {{ $penyewa ? $active : $inactive }}">
                    <i class="fas fa-users w-5 text-center text-lg {{ $penyewa ? 'text-green-600' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span class="text-sm">Data Penyewa</span>
                </a>
                
                <a href="{{ route('pengelola.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all duration-200 group mb-1 {{ $pengelola ? $active : $inactive }}">
                    <i class="fas fa-user-shield w-5 text-center text-lg {{ $pengelola ? 'text-green-600' : 'group-hover:scale-110 transition-transform' }}"></i>
                    <span class="text-sm">Data Pengelola</span>
                </a>

                {{-- ... MANAJEMEN ... --}}
                <div class="pt-6 pb-2 px-4 text-[11px] font-bold text-green-300 uppercase tracking-[0.15em]">Manajemen Cabang</div>

                {{-- Parepare Dropdown --}}
                <div class="sidebar-dropdown w-full mb-1">
                    <button type="button" class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 transition-all duration-200 {{ $parepare ? 'text-white font-semibold' : 'text-green-100 hover:bg-green-600/50 hover:text-white rounded-xl' }}"
                            aria-expanded="{{ $parepare ? 'true' : 'false' }}"
                            aria-controls="menu-parepare">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-location-dot w-5 text-center text-lg"></i>
                            <span class="text-sm">Parepare</span>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 {{ $parepare ? 'rotate-180' : '' }}"></i>
                    </button>
                    
                    <div id="menu-parepare" class="dropdown-menu overflow-hidden transition-all duration-300 ease-in-out bg-green-800/40 rounded-xl mt-1 mx-1" style="{{ $parepare ? 'max-height: 500px;' : 'max-height: 0px;' }}">
                        <div class="p-1.5 space-y-1">
                            @php $kamarPare = request()->fullUrlIs('*kamar*') && request()->fullUrlIs('*parepare*'); @endphp
                            <a href="{{ route('admin.cabangkamar.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-4 py-2.5 rounded-lg text-xs transition-all {{ $kamarPare ? 'bg-white text-green-700 shadow-sm' : 'text-green-100 hover:bg-green-600/50 hover:text-white' }}">
                                <i class="fas fa-bed w-4"></i>Data Kamar
                            </a>

                            @php $villaPare = request()->fullUrlIs('*layanan-villa*') && request()->fullUrlIs('*parepare*'); @endphp
                            <a href="{{ route('admin.cabanglayanan-villa.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-4 py-2.5 rounded-lg text-xs transition-all {{ $villaPare ? 'bg-white text-green-700 shadow-sm' : 'text-green-100 hover:bg-green-600/50 hover:text-white' }}">
                                <i class="fa-solid fa-bell-concierge w-4"></i>Layanan Villa
                            </a>

                            @php $infoPare = request()->fullUrlIs('*informasi-cabang*') && request()->fullUrlIs('*parepare*'); @endphp
                            <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-4 py-2.5 rounded-lg text-xs transition-all {{ $infoPare ? 'bg-white text-green-700 shadow-sm' : 'text-green-100 hover:bg-green-600/50 hover:text-white' }}">
                                <i class="fa-solid fa-circle-info w-4"></i>Informasi Cabang
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Pangkep Dropdown --}}
                <div class="sidebar-dropdown w-full mb-1">
                    <button type="button" class="dropdown-toggle flex items-center justify-between w-full px-4 py-3 transition-all duration-200 {{ $pangkep ? 'text-white font-semibold' : 'text-green-100 hover:bg-green-600/50 hover:text-white rounded-xl' }}"
                            aria-expanded="{{ $pangkep ? 'true' : 'false' }}" aria-controls="menu-pangkep">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-location-dot w-5 text-center text-lg"></i>
                                <span class="text-sm">Pangkep</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300 {{ $pangkep ? 'rotate-180' : '' }}"></i>
                    </button>
                    
                    <div id="menu-pangkep" class="dropdown-menu overflow-hidden transition-all duration-300 ease-in-out bg-green-800/40 rounded-xl mt-1 mx-1" style="{{ $pangkep ? 'max-height: 500px;' : 'max-height: 0px;' }}">
                        <div class="p-1.5 space-y-1">
                            @php $kamarPangkep = request()->fullUrlIs('*kamar*') && request()->fullUrlIs('*pangkep*'); @endphp
                            <a href="{{ route('admin.cabangkamar.index', ['lokasi' => 'pangkep']) }}" class="flex gap-3 items-center px-4 py-2.5 rounded-lg text-xs transition-all {{ $kamarPangkep ? 'bg-white text-green-700 shadow-sm' : 'text-green-100 hover:bg-green-600/50 hover:text-white' }}">
                                <i class="fas fa-bed w-4"></i>Data Kamar
                            </a>

                            @php $infoPangkep = request()->fullUrlIs('*informasi-cabang*') && request()->fullUrlIs('*pangkep*'); @endphp
                            <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => 'pangkep']) }}" class="flex gap-3 items-center px-4 py-2.5 rounded-lg text-xs transition-all {{ $infoPangkep ? 'bg-white text-green-700 shadow-sm' : 'text-green-100 hover:bg-green-600/50 hover:text-white' }}">
                                <i class="fa-solid fa-circle-info w-4"></i>Informasi Cabang
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Pembayaran --}}
                <a href="#" class="flex items-center gap-3 px-4 py-3 transition-all duration-200 group mb-1 {{ $inactive }}">
                    <i class="fas fa-wallet w-5 text-center text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Transaksi</span>
                </a>

                {{-- ... LAPORAN ... --}}
                <div class="pt-6 pb-2 px-4 text-[11px] font-bold text-green-300 uppercase tracking-[0.15em]">Laporan & Analitik</div>
                
                <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 px-4 py-3 transition-all duration-200 group mb-1 {{ $inactive }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="text-sm font-medium">Laporan Terpadu</span>
                </a>

                {{-- Setting --}}
                <div class="mt-4 pt-4 border-t border-green-600/50">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 transition-all duration-200 group mb-1 {{ $inactive }}">
                        <i class="fa-solid fa-sliders w-5 text-center text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                        <span class="text-sm font-medium">Pengaturan</span>
                    </a>
                </div>
            </nav>
        </div>

        {{-- FOOTER SIDEBAR (USER PROFILE) --}}
        <div class="p-4">
            <div class="bg-green-800/50 border border-green-600/50 rounded-2xl p-4 shadow-inner">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <img src="{{ Auth::user()->foto_profil ? asset('storage/' . Auth::user()->foto_profil) . '?t=' . time() : 'https://ui-avatars.com/api/?name=' . (Auth::user()->nama_lengkap ?? 'A') . '&background=ffffff&color=15803d' }}"
                            class="size-10 rounded-xl border-2 border-white/20 object-cover shadow-sm">
                        <div class="absolute -bottom-1 -right-1 size-3 bg-emerald-400 border-2 border-green-800 rounded-full"></div>
                    </div>

                    <div class="overflow-hidden flex-1">
                        <p class="text-[10px] text-green-300 font-medium uppercase tracking-wider leading-none mb-1">Administrator</p>
                        <p class="text-sm font-bold text-white truncate" title="{{ Auth::user()->nama_lengkap }}">
                            {{ Auth::user()->nama_lengkap ?? 'Admin User' }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="group bg-red-500/10 hover:bg-red-500 p-2 rounded-lg transition-all duration-200" title="Logout">
                            <i class="fa-solid fa-power-off text-red-400 group-hover:text-white text-sm"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    {{-- Overlay untuk mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden transition-opacity md:hidden"></div>

</div>