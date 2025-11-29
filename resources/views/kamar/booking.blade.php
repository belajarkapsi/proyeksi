@extends('layout.master')
@section('title', 'Konfirmasi Pesanan')

@section('content')
@php
    // Jangan redirect dari view — set flag agar view menampilkan pesan tapi tetap berada di halaman
    $roomsMissing = false;
    if (!isset($rooms) || (is_object($rooms) && $rooms->isEmpty()) || (is_array($rooms) && empty($rooms))) {
        session()->flash('error', 'Silakan pilih kamar terlebih dahulu sebelum memesan.');
        $roomsMissing = true;
    }
@endphp

@php
    $firstRoom = $roomsMissing ? null : ($rooms->first() ?? null);
    $activeCabang = $firstRoom ? $firstRoom->cabang : ($cabang ?? null);
    $kategoriRaw = $activeCabang->kategori_cabang ?? '';
    $kategoriClean = strtolower(trim($kategoriRaw));
    $isVilla = ($kategoriClean === 'villa');

    // Default tanggal: tetap kosong jika tidak ada old() / check_in
    // (Diubah supaya tanggal tidak otomatis terisi — pengguna harus memilih sendiri.)
    $defaultTanggal = old('tanggal', old('check_in', ''));

    if ($isVilla && (!isset($services) || $services === null)) {
        try { $services = \App\Models\Service::all(); } catch (\Throwable $e) { $services = collect(); }
    }
@endphp

{{-- TOMBOL KEMBALI --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sticky top-20 z-30 pointer-events-none">
    @if(isset($cabang))
    <a href="{{ route('cabang.kamar.index', array_merge($cabang->route_params, request()->all())) }}" class="pointer-events-auto inline-flex items-center gap-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-gray-200 text-green-700 text-sm font-semibold rounded-full shadow-sm hover:shadow-md hover:bg-green-600 hover:text-white transition-all duration-300 group transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali / Edit Pilihan
    </a>
    @else
    <a href="{{ route('dashboard') }}" class="pointer-events-auto inline-flex items-center gap-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-gray-200 text-green-700 text-sm font-semibold rounded-full shadow-sm hover:shadow-md hover:bg-green-600 hover:text-white transition-all duration-300 group transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Dashboard
    </a>
    @endif
</div>

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        {{-- tampilkan session error (jika ada) --}}
        @if(session('error'))
            <div class="max-w-6xl mx-auto mb-6">
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Perhatian:</strong>
                    <span class="block mt-1 text-sm">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
        <div class="max-w-6xl mx-auto mb-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Oops! Ada masalah:</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- jika roomsMissing tampilkan notice sehingga user tetap di halaman booking --}}
        @if(!empty($roomsMissing) && $roomsMissing)
            <div class="max-w-6xl mx-auto mb-6">
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Perhatian:</strong>
                    <span class="block mt-1 text-sm">Silakan pilih kamar terlebih dahulu sebelum memesan. Gunakan tombol "Kembali / Edit Pilihan" atau tautan "+ Edit / Tambah" untuk memilih kamar.</span>
                </div>
            </div>
        @endif

        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-3xl font-bold text-gray-900">Konfirmasi Pesanan Anda</h1>
            <p class="text-gray-500 text-sm mt-1">Mohon periksa kembali detail pesanan sebelum melakukan pembayaran.</p>
        </div>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            {{-- KOLOM KIRI: FORM DATA DIRI --}}
            <div class="lg:col-span-7">
                @if(empty($roomsMissing) || !$roomsMissing)
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    @csrf

                    {{-- Input Hidden ID Kamar --}}
                    @foreach($rooms as $room)
                        <input type="hidden" name="kamar_ids[]" value="{{ $room->id_kamar }}">
                    @endforeach

                    <div class="p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-3 border-b pb-4">
                            <div class="bg-green-100 p-2 rounded-full text-green-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Informasi Penyewa</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" required value="{{ old('nama_lengkap', Auth::user()->nama_lengkap) }}" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed shadow-sm focus:ring-0" readonly />
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">No. Telepon</label>
                                <input type="number" name="telepon" required value="{{ old('no_telp', Auth::user()->no_telp) }}" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed shadow-sm focus:ring-0" readonly>
                            </div>
                            <div class="sm:col-span-6">
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Email</label>
                                <input type="email" name="email" required value="{{ old('email', Auth::user()->email) }}" class="mt-1 block w-full rounded-lg border-gray-200 bg-gray-50 text-gray-600 cursor-not-allowed shadow-sm focus:ring-0" readonly>
                            </div>
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-bold text-gray-800 mb-1">Tanggal Mulai Check-in</label>
                                <div class="relative">
                                    <!-- Visible date input: only required when not in villa mode -->
                                    <input
                                        type="date"
                                        name="tanggal"
                                        id="tanggal"
                                        value="{{ $defaultTanggal }}"
                                        @if (!$isVilla) required @endif
                                        class="pl-10 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors"
                                        form="bookingForm"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-6 py-4 lg:hidden border-t border-gray-100">
                        <button type="submit" class="w-full rounded-xl bg-green-600 px-6 py-3 text-white font-bold shadow hover:bg-green-700 transition-all active:scale-95">
                            Bayar Sekarang
                        </button>
                    </div>
                </form>
                @else
                <div class="p-6 sm:p-8 bg-white rounded-xl border border-gray-100">
                    <p class="text-sm text-gray-700">Anda belum memilih kamar. Silakan pilih kamar terlebih dahulu.</p>
                    <div class="mt-4">
                        @if(isset($cabang))
                            <a href="{{ route('cabang.kamar.index', array_merge($cabang->route_params, request()->all())) }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg">Pilih Kamar</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg">Kembali ke Dashboard</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- KOLOM KANAN: PANEL DINAMIS --}}
            <div class="lg:col-span-5 mt-8 lg:mt-0">
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden sticky top-24 border border-gray-100">
                    <div class="p-5 bg-gradient-to-r from-green-50 to-white border-b border-green-100">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="bg-green-100 text-green-600 p-1.5 rounded-lg">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </span>
                            Rincian Pesanan
                        </h2>
                    </div>

                    {{-- START IF / ELSE: VILLA atau NON-VILLA --}}
                    @if($isVilla)
                        <div class="p-6 space-y-6">

                            {{-- 1. DURASI GLOBAL VILLA --}}
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Durasi Sewa (Malam)</label>
                                <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden w-full sm:w-1/2">
                                    <button type="button" onclick="adjustVillaDuration(-1)" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 border-r border-gray-300 text-gray-600 font-bold text-lg transition">-</button>
                                    <input type="number" id="villa_duration" name="villa_duration" form="bookingForm" value="1" min="1" class="w-full text-center border-none focus:ring-0 text-gray-800 font-bold" readonly>
                                    <button type="button" onclick="adjustVillaDuration(1)" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 border-l border-gray-300 text-green-600 font-bold text-lg transition">+</button>
                                </div>
                            </div>

                            {{-- 2. LIST KAMAR (READONLY) --}}
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Terpilih</label>
                                    <a href="{{ route('cabang.kamar.index', array_merge($cabang->route_params ?? [], request()->all())) }}" class="text-xs text-green-600 hover:underline hover:text-green-800 font-semibold transition">+ Edit / Tambah</a>
                                </div>

                                <div class="space-y-3 bg-gray-50 p-3 rounded-xl border border-gray-200">
                                    @foreach($rooms as $room)
                                    <div class="villa-room-item flex justify-between items-start" data-price="{{ $room->harga_kamar }}">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-white border border-gray-200 overflow-hidden flex-shrink-0">
                                                <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-gray-800">{{ $room->tipe_kamar }} - No. {{ $room->no_kamar }}</div>
                                                <div class="text-xs text-gray-500">Rp{{ number_format($room->harga_kamar, 0, ',', '.') }} / malam</div>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="durasi[{{ $room->id_kamar }}]" value="1" class="villa-final-days-input" form="bookingForm">
                                    @endforeach
                                </div>

                                {{-- wajib: kamar_ids hidden (kunci villa) --}}
                                @foreach($rooms as $room)
                                    <input type="hidden" name="kamar_ids[]" value="{{ $room->id_kamar }}" form="bookingForm">
                                @endforeach

                                {{-- NOTE: removed duplicate hidden_tanggal here to avoid duplicate 'tanggal' fields --}}
                            </div>

                            {{-- 3. LAYANAN TAMBAHAN --}}
                            @if($services->isNotEmpty())
                            <div>
                                <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Layanan Tambahan</label>
                                <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                                    @php
                                        $reqServices = request()->input('services', []);
                                        $reqQty = request()->input('service_quantity', []);
                                    @endphp

                                    @foreach($services as $service)
                                        @php
                                            $isGazebo = stripos($service->name, 'gazebo') !== false;
                                            $isChecked = in_array($service->id, $reqServices);
                                            $qty = $reqQty[$service->id] ?? 0;
                                            if($isChecked && $qty == 0) $qty = 1;
                                            if($qty > 0) $isChecked = true;
                                        @endphp

                                        <div class="villa-service-item flex items-center justify-between p-3 border rounded-xl transition-all group {{ $isChecked ? 'border-green-500 bg-green-50/30' : 'border-gray-200 bg-white' }}">
                                            <div class="flex-1 pr-2">
                                                <div class="text-sm font-bold text-gray-800 group-hover:text-green-700 transition">{{ $service->name }}</div>
                                                <div class="text-xs text-gray-500">IDR {{ number_format($service->price) }}</div>

                                                {{-- hidden inputs --}}
                                                <input type="checkbox" class="hidden service-check" id="svc-{{ $service->id }}" name="services[]" form="bookingForm" value="{{ $service->id }}" data-price="{{ $service->price }}" @checked($isChecked)>
                                                <input type="hidden" id="qty-{{ $service->id }}" name="service_quantity[{{ $service->id }}]" value="{{ $qty }}" form="bookingForm">
                                            </div>

                                            @if($isGazebo)
                                                <div class="flex items-center bg-gray-100 rounded-lg border border-gray-200 h-8">
                                                    <button type="button" onclick="updateServiceUI({{ $service->id }}, -1, 4)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-red-500 hover:bg-red-50 rounded-l-lg transition font-bold">-</button>
                                                    <span id="disp-{{ $service->id }}" class="w-6 text-center text-xs font-bold text-gray-800">{{ $qty }}</span>
                                                    <button type="button" onclick="updateServiceUI({{ $service->id }}, 1, 4)" class="w-8 h-full flex items-center justify-center text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-r-lg transition font-bold">+</button>
                                                </div>
                                            @else
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" id="vis-check-{{ $service->id }}" onchange="toggleServiceUI({{ $service->id }})" class="sr-only peer" @checked($isChecked)>
                                                    <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-green-600 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                                                </label>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- 4. SUMMARY VILLA --}}
                            <div class="border-t border-dashed border-gray-300 pt-4 space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Total Sewa (<span id="villa_nights_disp">1</span> malam)</span>
                                    <span id="villa_room_total" class="font-medium">Rp0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Total Layanan</span>
                                    <span id="villa_service_total" class="font-medium">Rp0</span>
                                </div>
                            </div>
                        </div>

                    @else
                        {{-- B. TAMPILAN STANDARD (HOTEL/KOS) --}}
                        <div class="p-6 max-h-[500px] overflow-y-auto custom-scrollbar space-y-6">
                            @foreach($rooms as $room)
                            <div class="room-item flex flex-col gap-3 pb-4 border-b border-gray-100 last:border-0" data-price="{{ $room->harga_kamar }}">
                                <div class="flex gap-4 items-center">
                                    <div class="h-16 w-16 shrink-0 overflow-hidden rounded-lg border border-gray-200">
                                        <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="h-full w-full object-cover">
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-gray-900">Kamar No. {{ $room->no_kamar }}</p>
                                        <p class="text-xs text-green-600 font-semibold">Rp{{ number_format($room->harga_kamar, 0, ',', '.') }} /malam</p>
                                    </div>
                                </div>

                                <div class="bg-gray-50 p-3 rounded-lg">
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="text-xs font-medium text-gray-600">Pilih Satuan:</label>
                                        <select class="unit-select text-xs border-gray-300 rounded shadow-sm focus:ring-green-500 focus:border-green-500 py-1">
                                            <option value="1">Hari</option>
                                            <option value="7">Minggu</option>
                                            <option value="30">Bulan</option>
                                            <option value="365">Tahun</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center justify-between bg-white border border-gray-200 rounded-md p-1">
                                        <button type="button" class="btn-minus w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded transition-colors font-bold">-</button>
                                        <span class="display-amount font-bold text-gray-800 text-sm">1</span>
                                        <button type="button" class="btn-plus w-8 h-8 flex items-center justify-center bg-gray-100 hover:bg-green-100 text-gray-600 hover:text-green-600 rounded transition-colors font-bold">+</button>
                                    </div>
                                    <input type="hidden" name="durasi[{{ $room->id_kamar }}]" form="bookingForm" class="final-days-input" value="1">
                                </div>

                                <div class="flex justify-between items-center text-xs pt-1">
                                    <span class="text-gray-500 detail-text">1 Hari</span>
                                    <span class="font-bold text-gray-800 subtotal-display">Rp{{ number_format($room->harga_kamar, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                    {{-- END IF/ELSE VILLA / NON-VILLA --}}

                    {{-- TOTAL BAYAR --}}
                    <div class="bg-gray-100 p-6 border-t border-gray-200">
                        <div class="flex justify-between items-end pt-2">
                            <span class="text-base font-bold text-gray-900">Total Bayar</span>
                            <span class="text-2xl font-bold text-green-700" id="grand_total">Rp0</span>
                        </div>

                        <button type="submit" form="bookingForm" class="w-full mt-6 hidden lg:flex justify-center items-center rounded-xl bg-green-600 px-6 py-4 text-base font-bold text-white shadow-lg hover:bg-green-700 transition-all transform hover:-translate-y-0.5">
                            Konfirmasi & Bayar
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- //JS LOGIC -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isVilla = {{ $isVilla ? 'true' : 'false' }};
    const formatRupiah = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
    const $ = (s) => document.querySelector(s);
    const $$ = (s) => Array.from(document.querySelectorAll(s));
    const grandTotalEl = $('#grand_total');

    // sync visible tanggal with hidden (if any)
    const tanggalInput = document.getElementById('tanggal');
    const hiddenTanggal = document.getElementById('hidden_tanggal');
    if (tanggalInput && hiddenTanggal) {
        tanggalInput.addEventListener('change', () => {
            hiddenTanggal.value = tanggalInput.value;
        });
    }

    if (isVilla) {
        const durationInput = document.getElementById('villa_duration');
        const nightsDisp = document.getElementById('villa_nights_disp');
        const inlineServicesSubmit = document.getElementById('inlineServicesSubmit');
        const topCancel = document.getElementById('top-cancel-btn');

        window.calcVillaTotal = function() {
            const duration = Math.max(1, parseInt(durationInput?.value || '1', 10));
            let roomTotal = 0;
            let serviceTotal = 0;
            $$('.villa-room-item').forEach(item => {
                const price = Number(item.dataset.price || 0);
                roomTotal += price * duration;
            });
            $$('.villa-final-days-input').forEach(inp => { inp.value = duration; });
            $$('.service-check').forEach(chk => {
                const id = chk.value;
                const price = Number(chk.dataset.price || 0);
                const qtyEl = document.getElementById('qty-' + id);
                const qty = qtyEl ? Math.max(0, parseInt(qtyEl.value || '0', 10)) : (chk.checked ? 1 : 0);
                if (chk.checked && qty > 0) serviceTotal += price * qty;
            });
            const grand = roomTotal + serviceTotal;
            if (nightsDisp) nightsDisp.innerText = duration;
            const roomTotalEl = document.getElementById('villa_room_total');
            const servTotalEl = document.getElementById('villa_service_total');
            if (roomTotalEl) roomTotalEl.innerText = formatRupiah(roomTotal);
            if (servTotalEl) servTotalEl.innerText = formatRupiah(serviceTotal);
            if (grandTotalEl) grandTotalEl.innerText = formatRupiah(grand);
            const inlineRooms = $('#inlineRoomsSubtotal');
            const inlineServices = $('#inlineServicesSubtotal');
            const inlineGrand = $('#inlineGrandTotal');
            if (inlineRooms) inlineRooms.innerText = 'IDR ' + String(roomTotal).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            if (inlineServices) inlineServices.innerText = 'IDR ' + String(serviceTotal).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            if (inlineGrand) inlineGrand.innerText = 'IDR ' + String(grand).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        };

        window.adjustVillaDuration = function(change) {
            if (!durationInput) return;
            let v = Math.max(1, parseInt(durationInput.value || '1', 10) + change);
            durationInput.value = v;
            calcVillaTotal();
        };

        window.toggleServiceUI = function(id) {
            const vis = document.getElementById('vis-check-' + id);
            const hid = document.getElementById('svc-' + id);
            const qty = document.getElementById('qty-' + id);
            if (!hid) return;
            hid.checked = !!(vis && vis.checked);
            if (qty) qty.value = hid.checked ? (Number(qty.value) || 1) : 0;
            calcVillaTotal();
        };

        window.updateServiceUI = function(id, change, max = 10) {
            const qty = document.getElementById('qty-' + id);
            const disp = document.getElementById('disp-' + id);
            const hid = document.getElementById('svc-' + id);
            if (!qty) return;
            let cur = Math.max(0, parseInt(qty.value || '0', 10) + change);
            if (cur > max) cur = max;
            qty.value = cur;
            if (disp) disp.innerText = cur;
            if (hid) hid.checked = cur > 0;
            calcVillaTotal();
        };

        if (durationInput) durationInput.addEventListener('change', calcVillaTotal);
        $$('.service-check').forEach(chk => chk.addEventListener('change', calcVillaTotal));
        $$('.service-check').forEach(chk => chk.addEventListener('input', calcVillaTotal));
        $$('.villa-final-days-input').forEach(i => i.addEventListener('change', calcVillaTotal));
        $$('.villa-room-item').forEach(i => i.addEventListener('change', calcVillaTotal));

        if (inlineServicesSubmit) {
            inlineServicesSubmit.addEventListener('click', function(e) {
                e.preventDefault();
                $$('.service-check').forEach(hid => {
                    const id = hid.value;
                    const vis = document.getElementById('vis-check-' + id);
                    const qty = document.getElementById('qty-' + id);
                    if (vis) hid.checked = !!vis.checked;
                    if (!hid.checked && qty) qty.value = 0;
                    if (hid.checked && qty && Number(qty.value) <= 0) qty.value = 1;
                });
                $$('.service-check').forEach(h => h.disabled = true);
                $$('.service-check').forEach(h => {
                    const id = h.value;
                    const vis = document.getElementById('vis-check-' + id);
                    if (vis) vis.disabled = true;
                    const qty = document.getElementById('qty-' + id);
                    if (qty) qty.disabled = true;
                });
                inlineServicesSubmit.disabled = true;
                inlineServicesSubmit.classList.add('opacity-60', 'cursor-not-allowed');
                inlineServicesSubmit.innerText = 'Layanan Terkunci — Tekan Batal untuk Ubah';
                window.__villa_services_locked = true;
                if (!document.getElementById('villa-locked-notice')) {
                    const notice = document.createElement('div');
                    notice.id = 'villa-locked-notice';
                    notice.className = 'mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm text-yellow-900';
                    notice.innerText = 'Pilihan layanan disimpan sementara. Silakan pilih kamar lalu tekan "Pesan". Untuk mengubah layanan tekan "Batalkan".';
                    inlineServicesSubmit.closest('section').appendChild(notice);
                }
                const villaAll = document.getElementById('villa-all');
                if (villaAll) villaAll.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        if (topCancel) {
            if (!topCancel.__villa_unlock_attached) {
                topCancel.addEventListener('click', function() {
                    $$('.service-check').forEach(h => { h.disabled = false; h.checked = false; });
                    $$('.service-check').forEach(h => {
                        const id = h.value;
                        const vis = document.getElementById('vis-check-' + id);
                        if (vis) vis.disabled = false, vis.checked = false;
                        const qty = document.getElementById('qty-' + id);
                        if (qty) { qty.disabled = false; qty.value = 0; }
                    });
                    const notice = document.getElementById('villa-locked-notice');
                    if (notice) notice.remove();
                    if (inlineServicesSubmit) {
                        inlineServicesSubmit.disabled = false;
                        inlineServicesSubmit.innerText = 'Lanjutkan ke Form Pemesanan';
                        inlineServicesSubmit.classList.remove('opacity-60', 'cursor-not-allowed');
                    }
                    window.__villa_services_locked = false;
                    calcVillaTotal();
                });
                topCancel.__villa_unlock_attached = true;
            }
        }

        calcVillaTotal();
    } else {
        const updateGrandTotal = () => {
            let total = 0;
            document.querySelectorAll('.room-item').forEach(item => {
                const price = parseFloat(item.getAttribute('data-price')) || 0;
                const daysInput = item.querySelector('.final-days-input');
                const days = daysInput ? Math.max(1, parseInt(daysInput.value || '1', 10)) : 1;
                total += price * days;
            });
            if (grandTotalEl) grandTotalEl.innerText = formatRupiah(total);
        };

        const updateRoom = (roomElement) => {
            const unitVal = parseInt(roomElement.querySelector('.unit-select').value || '1');
            const unitText = roomElement.querySelector('.unit-select').options[roomElement.querySelector('.unit-select').selectedIndex].text;
            let amount = parseInt(roomElement.querySelector('.display-amount').innerText || '1', 10);
            const totalDays = amount * unitVal;
            const finalDaysInput = roomElement.querySelector('.final-days-input');
            if (finalDaysInput) finalDaysInput.value = totalDays;
            const price = parseFloat(roomElement.getAttribute('data-price')) || 0;
            const subtotal = price * totalDays;
            const subtotalDisplay = roomElement.querySelector('.subtotal-display');
            if (subtotalDisplay) subtotalDisplay.innerText = formatRupiah(subtotal);
            const detailText = roomElement.querySelector('.detail-text');
            if (detailText) detailText.innerText = `${amount} ${unitText}`;
            updateGrandTotal();
        };

        document.querySelectorAll('.room-item').forEach(room => {
            const btnMinus = room.querySelector('.btn-minus');
            const btnPlus = room.querySelector('.btn-plus');
            const display = room.querySelector('.display-amount');
            const unitSelect = room.querySelector('.unit-select');

            if (btnMinus) btnMinus.addEventListener('click', () => {
                let current = Math.max(1, parseInt(display.innerText || '1', 10));
                if (current > 1) { display.innerText = current - 1; updateRoom(room); }
            });

            if (btnPlus) btnPlus.addEventListener('click', () => {
                let current = Math.max(1, parseInt(display.innerText || '1', 10));
                display.innerText = current + 1;
                updateRoom(room);
            });

            if (unitSelect) unitSelect.addEventListener('change', () => {
                display.innerText = 1;
                updateRoom(room);
            });

            updateRoom(room);
        });
    }
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: #f9fafb; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
.custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
</style>
@endsection
