@extends('layout.master')
@section('title', isset($cabang) ? 'Daftar Kamar - ' . $cabang->nama_cabang : 'Semua Daftar Kamar')

@section('content')

@php
    // ================= DATA PREPARATION =================
    $allRooms = $rooms instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rooms->items() : $rooms;
    $allRooms = collect($allRooms);

    // Deteksi Mode Villa
    $isVilla = isset($cabang) && strtolower(trim($cabang->kategori_cabang ?? '')) === 'villa';

    // FILTER DATA KHUSUS KOST
    if (!$isVilla) {
        $ekonomis = $allRooms->filter(function($r) {
            return $r->status === 'Tersedia' && stripos($r->tipe_kamar ?? '', 'Ekonomis') !== false;
        });
        $standard = $allRooms->filter(function($r) {
            return $r->status === 'Tersedia' && stripos($r->tipe_kamar ?? '', 'Standar') !== false;
        });
        $occupied = $allRooms->filter(function($r) {
            return $r->status === 'Dihuni';
        });
        $maintenance = $allRooms->filter(function($r) {
            return $r->status === 'Perbaikan';
        });
    }
@endphp

<div class="container mx-auto px-4 md:px-6 py-8 relative min-h-screen">

    {{-- BREADCRUMB --}}
    <nav class="text-sm text-gray-600 mb-6 flex items-center gap-2 overflow-x-auto whitespace-nowrap">
        <a href="{{ route('dashboard') }}" class="hover:underline hover:text-green-600">Home</a>
        <span class="text-gray-400">/</span>
        @if(isset($cabang))
            <a href="{{ route('cabang.show', $cabang->route_params) }}" class="hover:underline hover:text-green-600">
                {{ $cabang->nama_cabang }}
            </a>
        @else
            <span>Semua Lokasi</span>
        @endif
        <span class="text-gray-400">/</span>
        <span class="text-green-600 font-semibold">Daftar Kamar</span>
    </nav>

    {{-- FILTER NAV (Hanya Tampil di Non-Villa) --}}
    @if(!$isVilla)
        <div class="sticky top-20 z-30 bg-white/95 backdrop-blur border-b border-gray-100 py-3 mb-8 -mx-6 px-6 md:mx-0 md:px-0 md:rounded-xl shadow-sm">
            <div class="flex items-center gap-3 overflow-x-auto no-scrollbar">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wide mr-2">Lompat ke:</span>
                @if($ekonomis->count() > 0) <a href="#ekonomis" class="px-4 py-2 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition">Ekonomis ({{ $ekonomis->count() }})</a> @endif
                @if($standard->count() > 0) <a href="#standard" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition">Standard ({{ $standard->count() }})</a> @endif
                @if($occupied->count() > 0) <a href="#occupied" class="px-4 py-2 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-300 hover:bg-gray-200 transition">Terisi ({{ $occupied->count() }})</a> @endif
                @if($maintenance->count() > 0) <a href="#maintenance" class="px-4 py-2 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-300 hover:bg-gray-200 transition">Perbaikan ({{ $maintenance->count() }})</a> @endif
            </div>
        </div>
    @endif

    {{-- TOMBOL RESET SELECT (FLOATING) --}}
    <button id="top-cancel-btn" type="button" onclick="exitSelectionMode()" class="fixed top-32 left-1/2 transform -translate-x-1/2 z-50 hidden items-center gap-2 bg-red-500 text-white px-5 py-2 rounded-full shadow-xl animate-bounce-once ring-4 ring-white/50 hover:bg-red-600 transition-transform active:scale-95 cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="text-xs font-bold whitespace-nowrap">Reset Pilihan</span>
    </button>

    {{-- FORM BOOKING UTAMA --}}
    <form id="bookingForm" action="{{ route('booking.checkout') }}" method="POST">
    @csrf

        <div class="space-y-12">
            {{-- VILLA MODE --}}
            @if($isVilla)
                @php
                    if (!isset($services) || $services === null) {
                        try { $services = \App\Models\Service::where('id_cabang', $cabang->id_cabang ?? null)->get(); } catch (\Throwable $e) { $services = collect(); }
                    }
                @endphp

                <input type="hidden" name="cabang_id" value="{{ $cabang->id_cabang }}" form="bookingForm">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    {{-- KOLOM KIRI: daftar unit --}}
                    <div class="lg:col-span-2 space-y-8">
                        <section id="villa-all">
                            <div class="flex items-center gap-3 mb-6 pl-2 border-l-4 border-green-500">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Daftar Unit Villa</h2>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">{{ $allRooms->count() }} Unit</span>
                            </div>

                            @if($allRooms->isNotEmpty())
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 select-none">
                                    @foreach($allRooms as $room)
                                        @php
                                            $status = $room->status;
                                            $isAvailable = $status === 'Tersedia';
                                            $isMaintenance = $status === 'Perbaikan';
                                        @endphp

                                        <div class="relative w-full {{ !$isAvailable ? 'opacity-60 grayscale-80 pointer-events-none' : '' }}">
                                            @include('components.room-card-item', [
                                                'room' => $room,
                                                'isAvailable' => $isAvailable
                                            ])
                                            @if(!$isAvailable)
                                                <div class="absolute inset-0 z-10 rounded-xl flex flex-col items-center justify-center backdrop-blur-[1px]
                                                    {{ $isMaintenance ? 'bg-orange-100/50 border-2 border-orange-300' : 'bg-red-50/40' }}">

                                                    @if($isMaintenance)
                                                        <div class="bg-orange-500 text-white p-3 rounded-full shadow-lg mb-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                        <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                                            Sedang Perbaikan
                                                        </span>
                                                    @else
                                                        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                                            Dihuni
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                    <p class="text-gray-500">Tidak ada unit di lokasi ini.</p>
                                </div>
                            @endif
                        </section>
                    </div>

                    {{-- KOLOM KANAN: layanan --}}
                    <div class="lg:col-span-1">
                        @if($services->isNotEmpty())
                            <div class="sticky top-24 bg-white p-5 rounded-2xl border border-gray-100 shadow-xl">
                                <div class="flex items-center gap-2 mb-4 border-b pb-4 border-gray-100">
                                    <div class="bg-orange-100 text-orange-600 p-1.5 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Layanan Tambahan</h3>
                                </div>

                                <div class="space-y-3 max-h-[50vh] overflow-y-auto pr-1 custom-scrollbar mb-6">
                                    @foreach($services as $service)
                                        @php $isGazebo = stripos($service->name, 'gazebo') !== false; @endphp

                                        <div class="service-card p-3 border rounded-xl hover:border-green-400 transition-all bg-white relative group">
                                            {{-- Hidden inputs for form (kirim) --}}
                                            <input type="checkbox"
                                                class="service-checkbox hidden"
                                                id="srv-check-{{ $service->id }}"
                                                name="services[]"
                                                value="{{ $service->id }}"
                                                data-price="{{ $service->price }}"
                                                form="bookingForm">

                                            <input type="hidden"
                                                id="srv-qty-{{ $service->id }}"
                                                name="service_quantity[{{ $service->id }}]"
                                                value="0"
                                                form="bookingForm">

                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-800 text-sm leading-tight">{{ $service->name }}</div>
                                                    <div class="text-xs font-semibold text-green-700 mt-0.5">IDR {{ number_format($service->price) }}</div>
                                                </div>

                                                @if(!$isGazebo)
                                                    <label for="visible-check-{{ $service->id }}" class="cursor-pointer">
                                                        <input type="checkbox" id="visible-check-{{ $service->id }}" onchange="toggleStandardService({{ $service->id }})" class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer">
                                                    </label>
                                                @endif
                                            </div>

                                            @if($isGazebo)
                                                <div class="flex items-center justify-between bg-gray-50 p-1 rounded-lg mt-1">
                                                    <span class="text-[10px] text-gray-500 font-bold pl-2 uppercase">Jumlah</span>
                                                    <div class="flex items-center">
                                                        <button type="button" onclick="updateServiceQty({{ $service->id }}, -1, 4)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-red-500 rounded transition">-</button>
                                                        <span id="display-qty-{{ $service->id }}" class="w-6 text-center text-sm font-bold text-gray-800">0</span>
                                                        <button type="button" onclick="updateServiceQty({{ $service->id }}, 1, 4)" class="w-7 h-7 flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-green-600 rounded transition">+</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mb-4">
                                    <textarea name="notes" rows="2" class="w-full bg-gray-50 rounded-lg border-gray-200 p-3 text-sm focus:ring-2 focus:ring-green-500 outline-none transition placeholder-gray-400" placeholder="Catatan tambahan (opsional)..."></textarea>
                                </div>

                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200/60 relative">
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between text-gray-600">
                                            <span>Subtotal Kamar <span id="panel-room-count" class="text-[10px] bg-green-100 text-green-800 px-1.5 py-0.5 rounded ml-1 font-bold">0</span></span>
                                            <span id="panel-room-total" class="font-medium">IDR 0</span>
                                        </div>
                                        <div class="flex justify-between text-gray-600">
                                            <span>Subtotal Layanan <span id="panel-service-count" class="text-[10px] bg-orange-100 text-orange-800 px-1.5 py-0.5 rounded ml-1 font-bold">0</span></span>
                                            <span id="panel-service-total" class="font-medium">IDR 0</span>
                                        </div>
                                        <div class="border-t border-dashed border-gray-300 my-2"></div>
                                        <div class="flex justify-between items-center">
                                            <span class="font-bold text-gray-800">Total Estimasi</span>
                                            <span id="panel-grand-total" class="font-bold text-lg text-green-700">IDR 0</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            {{-- UNTUK KOST --}}
            @else
                @if($ekonomis->isNotEmpty())
                    <section id="ekonomis" class="scroll-mt-40">
                        <div class="flex items-center gap-3 mb-6 pl-2 border-l-4 border-green-500">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Tipe Ekonomis</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 select-none">
                            @foreach($ekonomis as $room)
                                @include('components.room-card-item', ['room' => $room, 'isAvailable' => true])
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($standard->isNotEmpty())
                    <section id="standard" class="scroll-mt-40">
                        <div class="flex items-center gap-3 mb-6 pl-2 border-l-4 border-blue-500">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-800">Tipe Standard</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 select-none">
                            @foreach($standard as $room)
                                @include('components.room-card-item', ['room' => $room, 'isAvailable' => true])
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($maintenance->isNotEmpty())
                    <section id="maintenance" class="scroll-mt-40 bg-orange-50 rounded-3xl p-6 border border-orange-200">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-1.5 h-8 bg-orange-500 rounded-full"></div>
                            <h2 class="text-xl font-bold text-gray-800">Sedang Perbaikan / Maintenance</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($maintenance as $room)
                                <div class="relative pointer-events-none opacity-80 grayscale-30">
                                    @include('components.room-card-item', ['room' => $room, 'isAvailable' => false])

                                    <div class="absolute inset-0 z-10 flex flex-col items-center justify-center bg-orange-100/40 rounded-xl border-2 border-orange-300 border-dashed">
                                        <div class="bg-white p-2 rounded-full shadow-sm mb-2">
                                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-orange-800 font-bold text-xs uppercase tracking-wider bg-orange-100 px-2 py-1 rounded">Perbaikan</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($occupied->isNotEmpty())
                    <section id="occupied" class="scroll-mt-40 bg-gray-50 rounded-3xl p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-6 opacity-60">
                            <div class="w-1.5 h-8 bg-gray-500 rounded-full"></div>
                            <h2 class="text-xl font-bold text-gray-700">Sedang Dihuni / Terisi</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 opacity-80">
                            @foreach($occupied as $room)
                                <div class="relative pointer-events-none">
                                    @include('components.room-card-item', ['room' => $room, 'isAvailable' => false])
                                    <div class="absolute inset-0 z-10 flex items-center justify-center bg-gray-200/30 rounded-xl">
                                        <span class="bg-gray-800 text-white text-xs font-bold px-3 py-1 rounded-full">TERISI</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif

            {{-- EMPTY --}}
            @if(!$isVilla && $ekonomis->isEmpty() && $standard->isEmpty() && $occupied->isEmpty() && $maintenance->isEmpty())
                <div class="text-center py-20">
                    <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Data Kamar Kosong</h3>
                </div>
            @endif
        </div>

        {{-- FLOATING ACTION BAR --}}
        <div id="floating-bar" class="fixed bottom-6 inset-x-0 flex justify-center items-end transform translate-y-48 transition-transform duration-500 z-50 pointer-events-none">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-2xl px-4 py-3 pointer-events-auto flex items-center gap-4 min-w-[300px] max-w-lg w-full mx-4">
                <div class="flex-1">
                    <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-0.5">Total Estimasi</div>
                    <div class="text-xl font-extrabold text-gray-900 leading-none" id="float-grand-total">IDR 0</div>
                </div>

                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl shadow-lg shadow-green-700/30 font-bold text-sm flex items-center gap-2 transition-transform active:scale-95 group">
                    <span>Pesan Sekarang</span>
                    <span id="selected-count" class="bg-green-600 border border-green-500 group-hover:bg-white group-hover:text-green-700 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm transition-colors">0</span>
                </button>
            </div>
        </div>

    </form>
</div>

@push('scripts')
<script>
    // ====== GLOBAL ======
    window.isVillaMode = {{ $isVilla ? 'true' : 'false' }};
</script>
<script src="{{ asset('js/daftar-kamar.js') }}"></script>
@endpush
@endsection
