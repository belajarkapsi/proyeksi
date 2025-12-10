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
    if (!$isVilla) {
        $ekonomis = $allRooms->filter(function($r) {
            $status = strtolower(trim($r->status_kamar ?? $r->status ?? ''));
            return (stripos($status, 'sedia') !== false) && (stripos($r->tipe_kamar ?? '', 'Ekonomis') !== false);
        });
        $standard = $allRooms->filter(function($r) {
            $status = strtolower(trim($r->status_kamar ?? $r->status ?? ''));
            return (stripos($status, 'sedia') !== false) && (stripos($r->tipe_kamar ?? '', 'Standar') !== false);
        });
        $occupied = $allRooms->filter(function($r) {
            $status = strtolower(trim($r->status_kamar ?? $r->status ?? ''));
            return stripos($status, 'sedia') === false;
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

                {{-- Hidden --}}
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
                                            $status = strtolower(trim($room->status_kamar ?? $room->status ?? ''));
                                            $isAvailable = stripos($status, 'sedia') !== false;
                                        @endphp

                                        <div class="relative w-full {{ !$isAvailable ? 'opacity-60 grayscale-80 pointer-events-none' : '' }}">
                                            {{-- room-card-item harus menyertakan elemen input[name="selected_rooms[]"] dengan id "check-{id}" dan data-price --}}
                                            @include('components.room-card-item', [
                                                'room' => $room,
                                                'isAvailable' => $isAvailable
                                            ])
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

            {{-- HOTEL / KOS MODE --}}
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

            {{-- EMPTY --}}
            @if(!$isVilla && $ekonomis->isEmpty() && $standard->isEmpty() && $occupied->isEmpty())
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
    const isVillaMode = {{ $isVilla ? 'true' : 'false' }};
    const formatRupiah = (n)=> new Intl.NumberFormat('id-ID',{ style:'currency', currency:'IDR', minimumFractionDigits:0 }).format(n);

    // ====== SELECTION LOGIC (dikembalikan seperti semula) ======
    const longPressDuration = 500;
    let pressTimer;
    let isSelectionMode = false;

    function handleCardClick(id, event) {
        if (event.target.closest('.detail-btn')) return;

        const card = document.getElementById('card-' + id);
        if (!card || card.getAttribute('data-available') != "1") return;

        if (isVillaMode) {
            toggleSelection(id);
        } else {
            if (isSelectionMode) {
                event.preventDefault();
                toggleSelection(id);
            } else {
                const url = card.getAttribute('data-booking-url');
                if (url) window.location.href = url;
            }
        }
    }

    function startPress(id) {
        if (isVillaMode) return;
        const card = document.getElementById('card-' + id);
        if (!card || card.getAttribute('data-available') != "1" || isSelectionMode) return;

        const loader = document.getElementById('loader-' + id);
        if (loader) {
            loader.style.width = '100%';
            loader.style.transitionDuration = '0.5s';
        }
        pressTimer = setTimeout(() => { enterSelectionMode(id); }, longPressDuration);
    }

    function cancelPress(id) {
        if (isVillaMode) return;
        const loader = document.getElementById('loader-' + id);
        if (loader) {
            loader.style.width = '0';
            loader.style.transitionDuration = '0.2s';
        }
        clearTimeout(pressTimer);
    }

    function enterSelectionMode(initialId) {
        isSelectionMode = true;
        if (navigator.vibrate) navigator.vibrate(50);
        toggleSelection(initialId);
    }

    function toggleSelection(id) {
        const checkbox = document.getElementById('check-' + id);
        const overlay = document.getElementById('overlay-' + id);
        const card = document.getElementById('card-' + id);
        if (!checkbox) return;

        checkbox.checked = !checkbox.checked;

        if (checkbox.checked) {
            if (overlay) overlay.classList.remove('hidden');
            if (card) card.classList.add('ring-2','ring-green-600','scale-[0.98]');
        } else {
            if (overlay) overlay.classList.add('hidden');
            if (card) card.classList.remove('ring-2','ring-green-600','scale-[0.98]');
        }

        if (!isVillaMode) {
            const checkedCount = document.querySelectorAll('input[name="selected_rooms[]"]:checked').length;
            if (checkedCount === 0) exitSelectionMode();
        }

        recalcTotals();
    }

    function exitSelectionMode() {
        document.querySelectorAll('input[name="selected_rooms[]"]').forEach(el => el.checked = false);
        document.querySelectorAll('.room-card').forEach(el => el.classList.remove('ring-2','ring-green-600','scale-[0.98]'));
        document.querySelectorAll('[id^="overlay-"]').forEach(el => el.classList.add('hidden'));

        // Reset layanan
        document.querySelectorAll('.service-checkbox').forEach(el => el.checked = false);
        document.querySelectorAll('input[name^="service_quantity"]').forEach(el => el.value = 0);
        document.querySelectorAll('[id^="display-qty-"]').forEach(el => el.innerText = '0');
        document.querySelectorAll('[id^="visible-check-"]').forEach(el => el.checked = false);

        isSelectionMode = false;
        recalcTotals();
    }

    // ====== SERVICES LOGIC ======
function updateServiceQty(id, change, maxLimit) {
    const qtyInput = document.getElementById('srv-qty-' + id);
    const displayEl = document.getElementById('display-qty-' + id);
    const checkbox = document.getElementById('srv-check-' + id);

    let currentQty = parseInt(qtyInput.value) || 0;
    let newQty = currentQty + change;
    if (newQty < 0) newQty = 0;
    if (typeof maxLimit === 'number' && newQty > maxLimit) newQty = maxLimit;

    qtyInput.value = newQty;
    if (displayEl) displayEl.innerText = newQty;
    if (checkbox) checkbox.checked = (newQty > 0);

    recalcTotals();
}

function toggleStandardService(id) {
    const visibleCheck = document.getElementById('visible-check-' + id);
    const hiddenCheck = document.getElementById('srv-check-' + id);
    const qtyInput = document.getElementById('srv-qty-' + id);

    if (!qtyInput || !hiddenCheck) return;

    hiddenCheck.checked = !!visibleCheck.checked;
    qtyInput.value = visibleCheck.checked ? 1 : 0;

    recalcTotals();
}

    // ====== CALCULATION (JANGAN HITUNG SERVICE JIKA TIDAK CHECKED) ======
    function recalcTotals() {
        let roomTotal = 0;
        let serviceTotal = 0;
        let selectedRoomsCount = 0;
        let selectedServicesItems = 0;

        // A. Hitung kamar: prioritas ambil dari checkbox selected_rooms[] data-price
        const roomCheckboxes = document.querySelectorAll('input[name="selected_rooms[]"]:checked');
        roomCheckboxes.forEach(cb => {
            let priceRaw = cb.getAttribute('data-price') || cb.dataset.price || null;

            // fallback: baca dari card dataset (jika ada)
            if ((!priceRaw || priceRaw === '' || priceRaw === '0') && cb.closest) {
                const card = cb.closest('.room-card');
                if (card) {
                    priceRaw = card.getAttribute('data-room-price') || card.getAttribute('data-price') || card.dataset.price || priceRaw;
                }
            }

            let price = 0;
            if (priceRaw !== null && priceRaw !== undefined) {
                const digits = String(priceRaw).replace(/\D/g, '');
                price = digits ? parseInt(digits,10) : 0;
            }

            roomTotal += price;
            selectedRoomsCount++;
        });

        // B. Hitung layanan: hanya bila checkbox service-checkbox benar-benar dicentang
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            if (!cb.checked) return;
            const id = cb.value;
            const price = parseFloat(cb.getAttribute('data-price')) || 0;
            const qtyInput = document.getElementById('srv-qty-' + id);
            let qty = qtyInput ? parseInt(qtyInput.value || 0, 10) : 1;
            if (qty === 0) qty = 1; // jika checkbox checked tapi qty 0, treat as 1
            serviceTotal += (price * qty);
            selectedServicesItems += qty;
        });

        const grandTotal = roomTotal + serviceTotal;
        const totalItems = selectedRoomsCount + selectedServicesItems;

        // Update panel & floating bar
        const panelRoom = document.getElementById('panel-room-total');
        const panelService = document.getElementById('panel-service-total');
        const panelGrand = document.getElementById('panel-grand-total');
        const panelRoomCount = document.getElementById('panel-room-count');
        const panelServiceCount = document.getElementById('panel-service-count');
        if (panelRoom) panelRoom.innerText = formatRupiah(roomTotal);
        if (panelService) panelService.innerText = formatRupiah(serviceTotal);
        if (panelGrand) panelGrand.innerText = formatRupiah(grandTotal);
        if (panelRoomCount) panelRoomCount.innerText = selectedRoomsCount + ' unit';
        if (panelServiceCount) panelServiceCount.innerText = selectedServicesItems + ' item';

        const floatGrand = document.getElementById('float-grand-total');
        const countBadge = document.getElementById('selected-count');
        const floatingBar = document.getElementById('floating-bar');
        const topCancel = document.getElementById('top-cancel-btn');
        if (floatGrand) floatGrand.innerText = formatRupiah(grandTotal);
        if (countBadge) countBadge.innerText = totalItems;

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

    // ====== PRE-SUBMIT HELPER (tetap ada, disederhanakan untuk robust) ======
    (function attachPreSubmitHelper(){
        const bookingForm = document.getElementById('bookingForm');
        if (!bookingForm) return;

        function removeDynamic() {
            Array.from(bookingForm.querySelectorAll('input[data-dynamic-service]')).forEach(n => n.remove());
        }

        function addHidden(name, value, attrs = {}) {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = name;
            inp.value = value;
            Object.keys(attrs).forEach(k => inp.setAttribute(k, attrs[k]));
            bookingForm.appendChild(inp);
            return inp;
        }

        // SIMPLIFIED: scan all srv-qty-* inputs and add services[] only when qty>0
       function buildServiceHiddenInputs() {
    removeDynamic();

    // visible toggles
    document.querySelectorAll('input[id^="visible-check-"]').forEach(el => {
        const m = el.id.match(/visible-check-(\d+)/);
        if (!m) return;
        const id = m[1];
        const qtyEl = document.getElementById('srv-qty-' + id) || document.getElementById('display-qty-' + id) || document.getElementById('disp-' + id);
        let rawQty = qtyEl ? (qtyEl.value !== undefined ? qtyEl.value : qtyEl.innerText) : '';
        let qty = parseInt(String(rawQty).replace(/\D/g,''), 10);
        if (isNaN(qty)) qty = 0;
        // if user ticked visible checkbox but qty is 0 -> assume 1
        if (el.checked && qty === 0) qty = 1;
        if (qty > 0) {
            addHidden('services[]', id, {'data-dynamic-service':'1'});
            addHidden('service_quantity[' + id + ']', qty, {'data-dynamic-qty':'1'});
        }
    });

    // hidden srv-check inputs (gazebo and others)
    document.querySelectorAll('input[id^="srv-check-"]').forEach(el => {
        const id = el.value;
        const qtyEl = document.getElementById('srv-qty-' + id) || document.getElementById('display-qty-' + id) || document.getElementById('disp-' + id);
        let rawQty = qtyEl ? (qtyEl.value !== undefined ? qtyEl.value : qtyEl.innerText) : '';
        let qty = parseInt(String(rawQty).replace(/\D/g,''), 10);
        if (isNaN(qty)) qty = 0;
        if (el.checked && qty === 0) qty = 1;
        if (el.checked && qty > 0) {
            addHidden('services[]', id, {'data-dynamic-service':'1'});
            addHidden('service_quantity[' + id + ']', qty, {'data-dynamic-qty':'1'});
        }
    });

    // counters (safety: pick up any visible counter elements)
    document.querySelectorAll('[id^="display-qty-"], [id^="disp-"]').forEach(el => {
        const mm = el.id.match(/(?:display-qty-|disp-)(\d+)/);
        if (!mm) return;
        const id = mm[1];
        let raw = (el.value !== undefined ? el.value : el.innerText) || '0';
        let qty = parseInt(String(raw).replace(/\D/g,''), 10);
        if (isNaN(qty)) qty = 0;
        if (qty > 0) {
            if (!bookingForm.querySelector('input[name="services[]"][value="'+id+'"]')) addHidden('services[]', id, {'data-dynamic-service':'1'});
            addHidden('service_quantity[' + id + ']', qty, {'data-dynamic-qty':'1'});
        }
    });

    // fallback hidden patterns
    document.querySelectorAll('input[id^="hidden_srv_"], input[id^="svc-"]').forEach(h => {
        const val = h.value;
        if (!val) return;
        const id = String(val);
        const qEl = document.getElementById('hidden_qty_' + id) || document.getElementById('srv-qty-' + id) || document.getElementById('qty-' + id);
        let qv = qEl ? (qEl.value !== undefined ? qEl.value : qEl.innerText) : '';
        let qty = parseInt(String(qv).replace(/\D/g,''), 10);
        if (isNaN(qty)) qty = 0;
        // if hidden srv exists but qty==0, assume 1 (only if the element appears intentionally)
        if (qty === 0) qty = 1;
        if (!bookingForm.querySelector('input[name="services[]"][value="'+id+'"]')) addHidden('services[]', id, {'data-dynamic-service':'1'});
        addHidden('service_quantity['+id+']', qty || 0, {'data-dynamic-qty':'1'});
    });

    const hasServiceHidden = bookingForm.querySelectorAll('input[name="services[]"]').length > 0;
    const hasKamarHidden = bookingForm.querySelectorAll('input[name="kamar_ids[]"], input[name="selected_rooms[]"]').length > 0;
    if (hasServiceHidden && !hasKamarHidden) addHidden('service_only', '1', {'data-dynamic-flag':'1'});
}


        bookingForm.addEventListener('submit', function(e){
            try { buildServiceHiddenInputs(); } catch(err){ console.error(err); }
        });

        Array.from(bookingForm.querySelectorAll('button[type="submit"], input[type="submit"]')).forEach(btn => {
            btn.addEventListener('click', function(evt){
                try { buildServiceHiddenInputs(); } catch(err){ console.error(err); }
            });
        });
    })();

    // run initial calc after DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        recalcTotals();
    });
</script>
@endpush
@endsection
