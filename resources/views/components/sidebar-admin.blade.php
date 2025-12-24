@php
    $dashboard = request()->routeIs('admin.dashboard');
    $penyewa = request()->routeIs('penyewa.*');
    $pengelola = request()->routeIs('pengelola.*');
    $parepare = request()->fullUrlIs('*parepare*');
    $pangkep = request()->fullUrlIs('*pangkep*');

    $active ='text-gray-800 bg-white/85 rounded-lg';
    $inactive = 'text-white hover:text-gray-800 hover:bg-white hover:rounded-lg';
@endphp

<div>
    {{-- Sidebar --}}
    <aside id="sidebar" class="bg-green-700 w-64 fixed top-0 left-0 bottom-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col h-full shadow-xl">

        <div class="flex-1 overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-green-800 [&::-webkit-scrollbar-thumb]:bg-green-500 hover:[&::-webkit-scrollbar-thumb]:bg-green-400">
            <div class="py-6 px-4">

                {{-- LOGO --}}
                <div class="tracking-wider mb-10 flex items-center gap-3 justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="size-12 object-contain">
                    <span class="text-xl font-bold text-white uppercase whitespace-nowrap">ADMIN PAGE</span>
                </div>

                {{-- Dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 {{ $dashboard ? $active : $inactive }}">
                    <i class="fas fa-tachometer-alt w-6 mt-1 text-center"></i>
                    <span class="font-bold text-xl">Dashboard</span>
                </a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- ... DATA PENGGUNA ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Data Pengguna</div>
                <a href="{{ route('penyewa.index') }}" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 hover:text-gray-800 hover:bg-white rounded-lg mb-1 {{ $penyewa ? $active : $inactive }}"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Penyewa</span></a>
                <a href="{{ route('pengelola.index') }}" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 hover:text-gray-800 hover:bg-white rounded-lg mb-1 {{ $pengelola ? $active : $inactive }}"><i class="fas fa-users w-6 text-center"></i><span class="font-medium">Data Pengelola</span></a>

                {{-- Pemisah --}}
                <div class="block border-b border-white"></div>

                {{-- ... MANAJEMEN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Manajemen Cabang</div>

                {{-- Parepare --}}
                <div class="sidebar-dropdown w-full mb-1">
                    <button type="button" class="dropdown-toggle flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-lg focus:outline-none transition-all duration-200 {{ $parepare ? $active : 'text-white bg-transparent hover:text-gray-800 hover:bg-white' }}"
                            aria-expanded="{{ $parepare ? 'true' : 'false' }}"
                            aria-controls="menu-parepare">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-map-location w-6 text-center"></i>
                            <span class="font-medium">Parepare</span>
                        </div>
                        {{-- Sebelum diklik --}}
                        <div class="flex items-center">
                            <svg class="{{ $parepare ? 'hidden' : 'block' }} size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                            {{-- Setelah diklik --}}
                            <svg class="{{ $parepare ? 'block' : 'hidden' }} size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/></svg>
                        </div>
                    </button>
                    {{-- Background Dropdown Gelap --}}
                    <div id="menu-parepare" class="dropdown-menu overflow-hidden max-h-0 transition-[max-height] duration-300 ease-in-out bg-green-800 rounded-lg mt-1 mx-1" style="{{ $parepare ? 'max-height: 500px;' : 'max-height: 0px;' }}">
                        <div class="py-2 space-y-1">
                            @php $kamarPare = request()->fullUrlIs('*kamar*') && request()->fullUrlIs('*parepare*'); @endphp
                            <a href="{{ route('admin.cabangkamar.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm transition-colors duration-200 {{ $kamarPare ? $active : 'text-white hover:text-gray-800 hover:bg-white' }}"><i class="fas fa-bed text-sm text-center"></i><span class="font-medium">Data Kamar</span></a>

                            @php $villaPare = request()->fullUrlIs('*layanan-villa*') && request()->fullUrlIs('*parepare*'); @endphp
                            <a href="{{ route('admin.cabanglayanan-villa.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm transition-colors duration-200 {{ $villaPare ? $active : 'text-white hover:text-gray-800 hover:bg-white' }}"><i class="fa-solid fa-bell-concierge text-sm text-center"></i><span class="font-medium">Layanan Villa</span></a>

                            @php $infoPare = request()->fullUrlIs('*informasi-cabang*') && request()->fullUrlIs('*parepare*'); @endphp
                            <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => 'parepare']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm transition-colors duration-200 {{ $infoPare ? $active : 'text-white hover:text-gray-800 hover:bg-white' }}"><i class="fa-solid fa-circle-info text-sm text-center"></i><span class="font-medium">Informasi Cabang</span></a>
                        </div>
                    </div>
                </div>

                {{-- Pangkep --}}
                <div class="sidebar-dropdown w-full mb-1">
                    <button type="button"
                            class="dropdown-toggle flex items-center justify-between w-full gap-3 px-3 py-2.5 rounded-lg focus:outline-none transition-all duration-200 {{ $pangkep ? $active : 'text-white bg-transparent hover:text-gray-800 hover:bg-white' }}"
                            aria-expanded="{{ $pangkep ? 'true' : 'false' }}" aria-controls="menu-pangkep">
                            <div class="flex items-center gap-3">
                                <i class="fa-solid fa-map-location w-6 text-center"></i>
                                <span class="font-medium">Pangkep</span>
                            </div>
                            {{-- Sebelum diklik --}}
                            <div class="flex items-center">
                                <svg class="{{ $pangkep ? 'hidden' : 'block' }} size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                {{-- Setelah diklik --}}
                                <svg class="{{ $pangkep ? 'block' : 'hidden' }} size-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 15 7-7 7 7"/></svg>
                            </div>
                        </button>
                    {{-- Background Dropdown Gelap --}}
                    <div id="menu-pangkep" class="dropdown-menu overflow-hidden max-h-0 transition-[max-height] duration-300 ease-in-out bg-green-800 rounded-lg mt-1 mx-1" style="{{ $pangkep ? 'max-height: 500px;' : 'max-height: 0px;' }}">
                        <div class="py-2 space-y-1">
                            @php $kamarPangkep = request()->fullUrlIs('*kamar*') && request()->fullUrlIs('*pangkep*'); @endphp
                            <a href="{{ route('admin.cabangkamar.index', ['lokasi' => 'pangkep']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm transition-colors duration-200 {{ $kamarPangkep ? $active : 'text-white hover:text-gray-800 hover:bg-white' }}"><i class="fas fa-bed text-sm text-center"></i><span class="font-medium">Data Kamar</span></a>

                            @php $infoPangkep = request()->fullUrlIs('*informasi-cabang*') && request()->fullUrlIs('*pangkep*'); @endphp
                            <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => 'pangkep']) }}" class="flex gap-3 items-center px-8 py-2 rounded-lg text-sm transition-colors duration-200 {{ $infoPangkep ? $active : 'text-white hover:text-gray-800 hover:bg-white' }}"><i class="fa-solid fa-circle-info text-sm text-center"></i><span class="font-medium">Informasi Cabang</span></a>
                        </div>
                    </div>
                </div>

                {{-- Pembayaran --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 hover:text-gray-800 hover:bg-white rounded-lg mb-1 {{ request()->routeIs('') ? $active : $inactive }}"><i class="fas fa-file-invoice-dollar w-6 text-center"></i><span class="font-medium">Transaksi</span></a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- ... LAPORAN ... --}}
                <div class="text-xs font-light text-gray-200 uppercase tracking-wider mt-4">Laporan</div>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 hover:text-gray-800 hover:bg-white rounded-lg mb-1 {{ request()->routeIs('') ? $active : $inactive }}"><i class="fa-solid fa-chart-area w-6 text-center"></i><span class="font-medium">Hunian</span></a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 hover:text-gray-800 hover:bg-white rounded-lg mb-1 {{ request()->routeIs('') ? $active : $inactive }}"><i class="fas fa-chart-line w-6 text-center"></i><span class="font-medium">Keuangan</span></a>

                <div class="block border-b border-white"></div> {{-- Pemisah --}}

                {{-- Setting --}}
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 transition-all duration-200 hover:text-gray-800 hover:bg-white rounded-lg mb-1 {{ request()->routeIs('') ? $active : $inactive }}">
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

    {{-- Overlay untuk mobile --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden transition-opacity md:hidden"></div>

</div>
