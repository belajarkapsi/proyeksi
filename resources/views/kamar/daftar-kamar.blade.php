@extends('layout.master')
@section('title', isset($cabang) ? 'Daftar Kamar - ' . $cabang->nama_cabang : 'Semua Daftar Kamar')

@section('content')

@php
    // ================= DATA PREPARATION =================
    $allRooms = $rooms instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rooms->items() : $rooms;
    $allRooms = collect($allRooms);

    // Deteksi Mode Villa
    $isVilla = isset($cabang) && strtolower(trim($cabang->kategori_cabang ?? '')) === 'villa';

    // FILTER DATA KHUSUS NON-VILLA (HOTEL/KOST)
    // Logika lama tetap dipertahankan untuk Hotel
    if (!$isVilla) {
        $ekonomis = $allRooms->filter(function($r) {
            $status = strtolower(trim($r->status_kamar ?? $r->status));
            return (stripos($status, 'sedia') !== false) && (stripos($r->tipe_kamar, 'Ekonomis') !== false);
        });
        $standard = $allRooms->filter(function($r) {
            $status = strtolower(trim($r->status_kamar ?? $r->status));
            return (stripos($status, 'sedia') !== false) && (stripos($r->tipe_kamar, 'Standar') !== false);
        });
        $occupied = $allRooms->filter(function($r) {
            $status = strtolower(trim($r->status_kamar ?? $r->status));
            return stripos($status, 'sedia') === false;
        });
    }
    // UNTUK VILLA: Kita gunakan $allRooms mentah-mentah agar urutan (1, 2, 3) tidak rusak
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

            {{-- ==================== TAMPILAN KHUSUS VILLA ==================== --}}
            @if($isVilla)
                @php
                    // Ambil Services
                    if (!isset($services) || $services === null) {
                        try { $services = \App\Models\Service::all(); } catch (\Throwable $e) { $services = collect(); }
                    }
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- KOLOM KIRI: DAFTAR UNIT VILLA (GRID GABUNGAN) --}}
                    <div class="lg:col-span-2 space-y-8">
                        <section id="villa-all">
                            <div class="flex items-center gap-3 mb-6 pl-2 border-l-4 border-green-500">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Daftar Unit Villa</h2>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full font-bold">{{ $allRooms->count() }} Unit</span>
                            </div>
        
                            @if($allRooms->isNotEmpty())
                                {{-- 
                                    SATU GRID UNTUK SEMUA:
                                    Kita tidak memisahkan Tersedia vs Terisi. 
                                    Mereka berada di loop yang sama agar urutan Layout Grid tetap rapi.
                                --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 select-none">
                                    @foreach($allRooms as $room)
                                        @php
                                            // Cek Status (Sedia/Tidak)
                                            $status = strtolower(trim($room->status_kamar ?? $room->status ?? ''));
                                            $isAvailable = stripos($status, 'sedia') !== false;
                                        @endphp

                                        {{-- Wrapper: Jika tidak sedia, kita bikin transparan & tidak bisa diklik --}}
                                        <div class="relative w-full {{ !$isAvailable ? 'opacity-60 grayscale-[80%] pointer-events-none' : '' }}">
                                            
                                            {{-- Component Render --}}
                                            @include('components.room-card-item', [
                                                'room' => $room, 
                                                'isAvailable' => $isAvailable
                                            ])
                                            
                                            {{-- Overlay ekstra untuk memastikan unit terisi tidak bisa diklik sama sekali --}}
                                            @if(!$isAvailable)
                                                <div class="absolute inset-0 z-10 bg-gray-100/10 cursor-not-allowed"></div>
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

                    {{-- KOLOM KANAN: PANEL LAYANAN (Sticky) --}}
                    <div class="lg:col-span-1">
                        @if($services->isNotEmpty())
                            <div class="sticky top-24 bg-white p-5 rounded-2xl border border-gray-100 shadow-xl shadow-gray-200/50">
                                <div class="flex items-center gap-2 mb-4 border-b pb-4 border-gray-100">
                                    <div class="bg-orange-100 text-orange-600 p-1.5 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-800">Layanan Tambahan</h3>
                                </div>

                                {{-- Daftar Layanan --}}
                                <div class="space-y-3 max-h-[50vh] overflow-y-auto pr-1 custom-scrollbar mb-6">
                                    @foreach($services as $service)
                                        @php $isGazebo = stripos($service->name, 'gazebo') !== false; @endphp

                                        <div class="service-card p-3 border rounded-xl hover:border-green-400 transition-all bg-white relative group">
                                            {{-- Input Hidden untuk kalkulasi JS dan kirim Form --}}
                                            <input type="checkbox" class="service-checkbox hidden" id="srv-check-{{ $service->id }}" name="services[]" value="{{ $service->id }}" data-price="{{ $service->price }}">
                                            <input type="hidden" id="srv-qty-{{ $service->id }}" name="service_quantity[{{ $service->id }}]" value="0">

                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex-1">
                                                    <div class="font-bold text-gray-800 text-sm leading-tight">{{ $service->name }}</div>
                                                    <div class="text-xs font-semibold text-green-700 mt-0.5">IDR {{ number_format($service->price) }}</div>
                                                </div>
                                                {{-- Checkbox Standar --}}
                                                @if(!$isGazebo)
                                                    <label for="visible-check-{{ $service->id }}" class="cursor-pointer">
                                                        <input type="checkbox" id="visible-check-{{ $service->id }}" onchange="toggleStandardService({{ $service->id }})" class="w-5 h-5 rounded border-gray-300 text-green-600 focus:ring-green-500 cursor-pointer">
                                                    </label>
                                                @endif
                                            </div>

                                            {{-- Counter Gazebo --}}
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

                                {{-- Catatan --}}
                                <div class="mb-4">
                                    <textarea name="notes" rows="2" class="w-full bg-gray-50 rounded-lg border-gray-200 p-3 text-sm focus:ring-2 focus:ring-green-500 outline-none transition placeholder-gray-400" placeholder="Catatan tambahan (opsional)..."></textarea>
                                </div>
                                
                                {{-- Rincian Harga (Receipt Style) --}}
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

            @else
            {{-- ==================== TAMPILAN HOTEL / KOS (LOGIKA LAMA - TETAP SEPERTI AWAL) ==================== --}}
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

                @if($occupied->isNotEmpty())
                    <section id="occupied" class="scroll-mt-40 bg-gray-50 rounded-3xl p-6 border border-gray-200">
                        <div class="flex items-center gap-3 mb-6 opacity-60">
                            <div class="w-1.5 h-8 bg-gray-500 rounded-full"></div>
                            <h2 class="text-xl font-bold text-gray-700">Terisi / Maintenance</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 opacity-80">
                            @foreach($occupied as $room)
                                @include('components.room-card-item', ['room' => $room, 'isAvailable' => false])
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif

            {{-- EMPTY STATE --}}
            @if(!$isVilla && $ekonomis->isEmpty() && $standard->isEmpty() && $occupied->isEmpty())
                <div class="text-center py-20">
                    <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Data Kamar Kosong</h3>
                </div>
            @endif
        </div>

        {{-- FLOATING ACTION BAR (Untuk Villa & Non-Villa saat seleksi) --}}
        <div id="floating-bar" class="fixed bottom-6 inset-x-0 flex justify-center items-end transform translate-y-48 transition-transform duration-500 z-50 pointer-events-none">
            <div class="bg-white border border-gray-200 rounded-2xl shadow-2xl px-4 py-3 pointer-events-auto flex items-center gap-4 min-w-[300px] max-w-lg w-full mx-4">
                
                {{-- Info Total Ringkas --}}
                <div class="flex-1">
                    <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-0.5">Total Estimasi</div>
                    <div class="text-xl font-extrabold text-gray-900 leading-none" id="float-grand-total">IDR 0</div>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl shadow-lg shadow-green-700/30 font-bold text-sm flex items-center gap-2 transition-transform active:scale-95 group">
                    <span>Pesan Sekarang</span>
                    {{-- Badge menghitung Kamar + Item Layanan --}}
                    <span id="selected-count" class="bg-green-600 border border-green-500 group-hover:bg-white group-hover:text-green-700 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full shadow-sm transition-colors">0</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')

<script>
    // ========== VARIABEL GLOBAL ==========
    const isVillaMode = {{ $isVilla ? 'true' : 'false' }};
    const longPressDuration = 500;
    let pressTimer;
    let isSelectionMode = false;

    // ========== FORMATTER RUPIAH ==========
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    // ========== LOGIKA INTERAKSI KAMAR ==========
    
    // 1. Handle Klik Kartu
    function handleCardClick(id, event) {
        if (event.target.closest('.detail-btn')) return; 

        const card = document.getElementById('card-' + id);
        // Pastikan card ada & tersedia
        if (!card || card.getAttribute('data-available') != "1") return;

        // VILLA: Langsung Select (Toggle)
        if (isVillaMode) {
            toggleSelection(id);
        } 
        // HOTEL: Logic Lama
        else {
            if (isSelectionMode) {
                event.preventDefault();
                toggleSelection(id);
            } else {
                const url = card.getAttribute('data-booking-url');
                if(url) window.location.href = url;
            }
        }
    }

    // 2. Start Long Press (Hanya Hotel)
    function startPress(id) {
        if (isVillaMode) return;
        const card = document.getElementById('card-' + id);
        if (card.getAttribute('data-available') != "1" || isSelectionMode) return;
        
        const loader = document.getElementById('loader-' + id);
        if(loader) {
            loader.style.width = '100%';
            loader.style.transitionDuration = '0.5s';
        }
        pressTimer = setTimeout(() => { enterSelectionMode(id); }, longPressDuration);
    }

    // 3. Cancel Long Press
    function cancelPress(id) {
        if (isVillaMode) return;
        const loader = document.getElementById('loader-' + id);
        if(loader) {
            loader.style.width = '0';
            loader.style.transitionDuration = '0.2s';
        }
        clearTimeout(pressTimer);
    }

    // 4. Masuk Mode Seleksi (Hotel)
    function enterSelectionMode(initialId) {
        isSelectionMode = true;
        if (navigator.vibrate) navigator.vibrate(50);
        toggleSelection(initialId);
    }

    // 5. Toggle Pilihan Kamar
    function toggleSelection(id) {
        const checkbox = document.getElementById('check-' + id);
        const overlay = document.getElementById('overlay-' + id);
        const card = document.getElementById('card-' + id);
        
        if(checkbox) checkbox.checked = !checkbox.checked;

        // Visual Feedback
        if (checkbox && checkbox.checked) {
            if(overlay) overlay.classList.remove('hidden');
            if(card) card.classList.add('ring-2', 'ring-green-600', 'scale-[0.98]');
        } else {
            if(overlay) overlay.classList.add('hidden');
            if(card) card.classList.remove('ring-2', 'ring-green-600', 'scale-[0.98]');
        }

        // Logic Non-Villa: Jika tidak ada yg dipilih, keluar mode seleksi
        if(!isVillaMode) {
            const checkedCount = document.querySelectorAll('input[name="selected_rooms[]"]:checked').length;
            if (checkedCount === 0) exitSelectionMode();
        } 
        
        recalcTotals(); 
    }

    // 6. Keluar Mode Seleksi & RESET SEMUA
    function exitSelectionMode() {
        // A. Reset Kamar
        document.querySelectorAll('input[name="selected_rooms[]"]').forEach(el => el.checked = false);
        document.querySelectorAll('.room-card').forEach(el => el.classList.remove('ring-2', 'ring-green-600', 'scale-[0.98]'));
        document.querySelectorAll('[id^="overlay-"]').forEach(el => el.classList.add('hidden'));

        // B. Reset Layanan
        document.querySelectorAll('.service-checkbox').forEach(el => el.checked = false);
        document.querySelectorAll('input[name^="service_quantity"]').forEach(el => el.value = 0);
        document.querySelectorAll('[id^="display-qty-"]').forEach(el => el.innerText = '0');
        document.querySelectorAll('[id^="visible-check-"]').forEach(el => el.checked = false);

        isSelectionMode = false;
        recalcTotals();
    }


    // ========== LOGIKA LAYANAN (SERVICES) ==========

    function updateServiceQty(id, change, maxLimit) {
        const qtyInput = document.getElementById('srv-qty-' + id);
        const displayEl = document.getElementById('display-qty-' + id);
        const checkbox = document.getElementById('srv-check-' + id);

        let currentQty = parseInt(qtyInput.value) || 0;
        let newQty = currentQty + change;

        if (newQty < 0) newQty = 0;
        if (newQty > maxLimit) newQty = maxLimit;

        qtyInput.value = newQty;
        displayEl.innerText = newQty;
        checkbox.checked = (newQty > 0);

        recalcTotals();
    }

    function toggleStandardService(id) {
        const visibleCheck = document.getElementById('visible-check-' + id);
        const hiddenCheck = document.getElementById('srv-check-' + id);
        const qtyInput = document.getElementById('srv-qty-' + id);

        hiddenCheck.checked = visibleCheck.checked;
        if(qtyInput) qtyInput.value = visibleCheck.checked ? 1 : 0;

        recalcTotals();
    }


    // ========== LOGIKA UTAMA: KALKULASI HARGA (KAMAR + LAYANAN) ==========

    function recalcTotals() {
        let roomTotal = 0;
        let serviceTotal = 0;
        
        let selectedRoomsCount = 0;
        let selectedServicesItems = 0;

        // A. Hitung Total Kamar
        const roomCheckboxes = document.querySelectorAll('input[name="selected_rooms[]"]:checked');
        roomCheckboxes.forEach(cb => {
            let price = parseFloat(cb.getAttribute('data-price')) || 0;
            roomTotal += price;
            selectedRoomsCount++;
        });

        // B. Hitung Total Layanan
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            const id = cb.value;
            const price = parseFloat(cb.getAttribute('data-price')) || 0;
            const qtyInput = document.getElementById('srv-qty-' + id);
            
            if (cb.checked) {
                let qty = qtyInput ? parseInt(qtyInput.value) : 1;
                if(qty === 0 && cb.checked) qty = 1; 
                
                serviceTotal += (price * qty);
                selectedServicesItems += qty;
            }
        });

        const grandTotal = roomTotal + serviceTotal;
        const totalItems = selectedRoomsCount + selectedServicesItems;

        // C. Update Panel Rincian (Kanan - Villa)
        const panelRoom = document.getElementById('panel-room-total');
        const panelService = document.getElementById('panel-service-total');
        const panelGrand = document.getElementById('panel-grand-total');
        const panelRoomCount = document.getElementById('panel-room-count');
        const panelServiceCount = document.getElementById('panel-service-count');

        if(panelRoom) panelRoom.innerText = formatRupiah(roomTotal);
        if(panelService) panelService.innerText = formatRupiah(serviceTotal);
        if(panelGrand) panelGrand.innerText = formatRupiah(grandTotal);
        if(panelRoomCount) panelRoomCount.innerText = selectedRoomsCount + ' unit';
        if(panelServiceCount) panelServiceCount.innerText = selectedServicesItems + ' item';

        // D. Update Floating Bar (Bawah - Universal)
        const floatGrand = document.getElementById('float-grand-total');
        const countBadge = document.getElementById('selected-count');
        const floatingBar = document.getElementById('floating-bar');
        const topCancel = document.getElementById('top-cancel-btn');

        if(floatGrand) floatGrand.innerText = formatRupiah(grandTotal);
        if(countBadge) countBadge.innerText = totalItems;

        // E. Tampilkan/Sembunyikan UI Floating Bar
        const hasSelection = totalItems > 0;

        if (hasSelection) {
            floatingBar.classList.remove('translate-y-48');
            topCancel.classList.remove('hidden');
            topCancel.classList.add('flex');
        } else {
            floatingBar.classList.add('translate-y-48');
            topCancel.classList.add('hidden');
            topCancel.classList.remove('flex');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        recalcTotals();
    });

</script>
@endpush
@endsection