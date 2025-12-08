@extends('layout.master')
@section('title', 'Konfirmasi Pesanan')

@section('content')
@php
    $roomsMissing = false;
    if (!isset($rooms) || (is_object($rooms) && $rooms->isEmpty()) || (is_array($rooms) && empty($rooms))) {
        session()->flash('error', 'Silakan pilih kamar terlebih dahulu sebelum memesan.');
        $roomsMissing = true;
    }

    $firstRoom = $roomsMissing ? null : ($rooms->first() ?? null);
    $activeCabang = $firstRoom ? $firstRoom->cabang : ($cabang ?? null);
    $kategoriRaw = $activeCabang->kategori_cabang ?? '';
    $kategoriClean = strtolower(trim($kategoriRaw));
    $isVilla = ($kategoriClean === 'villa');
    $defaultTanggal = old('tanggal', old('check_in', ''));
    if (!isset($services) || $services === null) {
        try { $services = \App\Models\Service::all(); } catch (\Throwable $e) { $services = collect(); }
    }
    $serviceOnly = $serviceOnly ?? false;
    $totalServicePrice = $totalServicePrice ?? 0;
@endphp

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

        @if($roomsMissing && !$serviceOnly)
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
            <div class="lg:col-span-7">
                @if(!$roomsMissing || $serviceOnly)
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    @csrf

                    @if(!$roomsMissing)
                        @foreach($rooms as $room)
                            <input type="hidden" name="kamar_ids[]" value="{{ $room->id_kamar }}">
                        @endforeach
                    @else
                        <input type="hidden" name="service_only" value="1">
                    @endif

                    @if(isset($services) && $services->isNotEmpty())
                        @php $requestQtys = request()->input('service_quantity', []); @endphp
                        @foreach($services as $srv)
                            @php
                                $qtyFromSrv = $srv->requested_qty ?? null;
                                $qtyFromReq = isset($requestQtys[$srv->id]) ? intval($requestQtys[$srv->id]) : null;
                                $qty = $qtyFromSrv !== null ? intval($qtyFromSrv) : ($qtyFromReq !== null ? $qtyFromReq : 0);
                            @endphp
                            @if($qty > 0)
                                <input type="hidden"
                                    name="services[]"
                                    value="{{ $srv->id }}"
                                    form="bookingForm"
                                    id="hidden_srv_{{ $srv->id }}"
                                    data-price="{{ $srv->price ?? $srv->harga ?? 0 }}">
                                <input type="hidden"
                                    name="service_quantity[{{ $srv->id }}]"
                                    value="{{ $qty > 0 ? $qty : 1 }}"
                                    form="bookingForm"
                                    id="hidden_qty_{{ $srv->id }}">
                            @endif
                        @endforeach
                    @endif

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
                                <label class="block text-sm font-bold text-gray-800 mb-1">Tanggal</label>
                                <div class="relative">
                                    <input
                                        type="date"
                                        name="tanggal"
                                        id="tanggal"
                                        value="{{ old('tanggal', $defaultTanggal) }}"
                                        required
                                        class="pl-10 block w-full rounded-xl border-gray-300 shadow-sm focus:ring-green-500 focus:border-green-500 transition-colors"
                                        form="bookingForm"
                                    >
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                @error('tanggal')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
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

                    @if($serviceOnly)
                        <div class="p-6 space-y-4">
                            <div class="text-sm text-gray-600">
                                <p class="font-medium">Kamu memesan layanan tanpa kamar.</p>
                                <p class="text-xs text-gray-500">Form di sebelah kiri tetap bisa dilengkapi. Tekan "Konfirmasi & Bayar" (tombol di bawah) untuk menyelesaikan pemesanan layanan.</p>
                            </div>

                            <div class="space-y-3 max-h-56 overflow-y-auto pr-2">
                                @if($services->isEmpty())
                                    <p class="text-sm text-gray-500">Tidak ada layanan terpilih.</p>
                                @else
                                    @foreach($services as $srv)
                                        @php
                                            $qty = $srv->requested_qty ?? 0;
                                            $price = floatval($srv->price ?? $srv->harga ?? 0);
                                            $line = $price * max(0, $qty);
                                        @endphp
                                        <div class="flex justify-between items-start bg-gray-50 p-3 rounded-lg border border-gray-200">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">{{ $srv->name }}</div>
                                                <div class="text-xs text-gray-500">Rp{{ number_format($price,0,',','.') }} x {{ $qty }} </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold text-gray-900">Rp{{ number_format($line,0,',','.') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="border-t border-dashed border-gray-300 pt-4 space-y-2 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Total Layanan</span>
                                    <span class="font-medium">Rp{{ number_format($totalServicePrice ?? 0,0,',','.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Grand Total</span>
                                    <span class="font-medium text-green-700">Rp{{ number_format($totalServicePrice ?? 0,0,',','.') }}</span>
                                </div>
                            </div>

                            <div class="pt-4">
                                @if(isset($cabang))
                                    <a href="{{ route('cabang.kamar.index', array_merge($cabang->route_params, request()->all())) }}" class="inline-block px-4 py-2 bg-white text-green-700 border border-green-200 rounded-lg shadow-sm hover:bg-green-50">Kembali / Tambah Kamar</a>
                                @endif
                            </div>
                        </div>

                    @else
                        @if($isVilla)
                            <div class="p-6 space-y-6">
                                <div>
                                    <label class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2 block">Durasi Sewa (Malam)</label>
                                    <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden w-full sm:w-1/2">
                                        <button type="button" onclick="adjustVillaDuration(-1)" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 border-r border-gray-300 text-gray-600 font-bold text-lg transition">-</button>
                                        <input type="number" id="villa_duration" name="villa_duration" form="bookingForm" value="1" min="1" class="w-full text-center border-none focus:ring-0 text-gray-800 font-bold" readonly>
                                        <button type="button" onclick="adjustVillaDuration(1)" class="px-3 py-2 bg-gray-50 hover:bg-gray-100 border-l border-gray-300 text-green-600 font-bold text-lg transition">+</button>
                                    </div>
                                </div>

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

                                    @foreach($rooms as $room)
                                        <input type="hidden" name="kamar_ids[]" value="{{ $room->id_kamar }}" form="bookingForm">
                                    @endforeach
                                </div>

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
                                                $qty = $reqQty[$service->id] ?? ($service->requested_qty ?? 0);
                                                if($isChecked && $qty == 0) $qty = 1;
                                                if($qty > 0) $isChecked = true;
                                            @endphp

                                            <div class="villa-service-item flex items-center justify-between p-3 border rounded-xl transition-all group {{ $isChecked ? 'border-green-500 bg-green-50/30' : 'border-gray-200 bg-white' }}">
                                                <div class="flex-1 pr-2">
                                                    <div class="text-sm font-bold text-gray-800 group-hover:text-green-700 transition">{{ $service->name }}</div>
                                                    <div class="text-xs text-gray-500">IDR {{ number_format($service->price) }}</div>

                                                    <input type="checkbox" class="hidden service-check" id="svc-{{ $service->id }}" name="services[]" form="bookingForm" value="{{ $service->id }}" data-price="{{ $service->price }}">
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
                    @endif

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

<script>
// Fungsi Global untuk Villa Duration (Harus di luar DOMContentLoaded agar bisa diakses onclick HTML)
window.adjustVillaDuration = function(change) {
    const durationInput = document.getElementById('villa_duration');
    const displayNights = document.getElementById('villa_nights_disp');
    
    if (durationInput) {
        let currentVal = parseInt(durationInput.value) || 1;
        let newVal = currentVal + change;
        
        if (newVal < 1) newVal = 1;
        
        // Update tampilan input
        durationInput.value = newVal;
        
        // Update text durasi malam
        if(displayNights) displayNights.textContent = newVal;

        // Update semua hidden input durasi per kamar
        document.querySelectorAll('.villa-final-days-input').forEach(input => {
            input.value = newVal;
        });

        // Trigger event untuk recalculate total
        const event = new Event('input', { bubbles: true });
        durationInput.dispatchEvent(event);
    }
};

// Fungsi Global untuk UI Service
window.toggleServiceUI = function(id) {
    const checkbox = document.getElementById('svc-' + id);
    if(checkbox) {
        checkbox.checked = !checkbox.checked;
        // Trigger perubahan event manually
        const event = new Event('change', { bubbles: true });
        checkbox.dispatchEvent(event);
    }
};

window.updateServiceUI = function(id, change, max) {
    const qtyInput = document.getElementById('qty-' + id);
    const dispSpan = document.getElementById('disp-' + id);
    const checkbox = document.getElementById('svc-' + id);
    
    if(qtyInput && dispSpan) {
        let val = parseInt(qtyInput.value) || 0;
        val += change;
        if(val < 0) val = 0;
        
        qtyInput.value = val;
        dispSpan.innerText = val;
        
        // Auto check/uncheck based on qty
        if(checkbox) {
            checkbox.checked = val > 0;
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            checkbox.dispatchEvent(event);
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm'); 
    if (!bookingForm) return;

    // --- Inisialisasi Elemen ID ---
    const grandTotalElement = document.getElementById('grand_total'); // Menggunakan ID yang benar: grand_total
    const villaRoomTotalElement = document.getElementById('villa_room_total');
    const villaServiceTotalElement = document.getElementById('villa_service_total');

    // --- LOGIKA KALENDER (Format dd-mm-yy & Min Date) ---
    const tanggalInput = document.getElementById('tanggal');
    if (tanggalInput) {
        // 1. Set minimal tanggal hari ini (Native HTML5 validation)
        const today = new Date().toISOString().split('T')[0];
        tanggalInput.setAttribute('min', today);

        // 2. Format dd-mm-yy menggunakan Flatpickr (Jika library tersedia)
        // Jika tidak ada flatpickr, akan tetap menggunakan native datepicker (yyyy-mm-dd) namun tetap dengan validasi min date
        if (typeof flatpickr !== 'undefined') {
            flatpickr(tanggalInput, {
                dateFormat: "d-m-Y", // Format sesuai permintaan
                minDate: "today",
                defaultDate: tanggalInput.value || "today",
                disableMobile: "true" 
            });
        }
    }

    // --- LOGIKA KALKULASI UTAMA ---
    function updateTotal() {
        let totalRoom = 0;
        let totalService = 0;

        // 1. Hitung Harga Kamar / Villa
        const villaDurationInput = document.getElementById('villa_duration');
        let duration = 1;
        
        if (villaDurationInput) {
            // Mode Villa (Satu durasi untuk semua unit)
            duration = parseInt(villaDurationInput.value) || 1;
        }

        // Ambil elemen kamar dari list visual (Rincian Pesanan)
        // Kita menggunakan class .villa-room-item (mode villa) dan .room-item (mode biasa)
        const roomItems = document.querySelectorAll('.villa-room-item, .room-item');
        
        if (roomItems.length > 0) {
            roomItems.forEach(item => {
                const price = parseFloat(item.dataset.price) || 0;
                let itemDurationDays = duration; // default: villa mode uses nights directly

                // Cek jika ini adalah item mode biasa yang punya input durasi sendiri
                const specificDurationInput = item.querySelector('.final-days-input');
                if (specificDurationInput) {
                    // --- NEW: read unit multiplier from unit-select inside the same room item ---
                    const unitSelect = item.querySelector('.unit-select');
                    const unitMultiplier = unitSelect ? (parseInt(unitSelect.value) || 1) : 1;
                    // specificDurationInput.value stores NUMBER OF UNITS (e.g., 2 means "2 minggu" if unit=7)
                    const unitCount = parseInt(specificDurationInput.value) || 1;

                    // itemDurationDays = jumlah hari sebenarnya = unitCount * unitMultiplier
                    itemDurationDays = unitCount * unitMultiplier;
                    
                    // Update tampilan subtotal per item (Mode Biasa) menggunakan hari sebenarnya
                    const subDisplay = item.querySelector('.subtotal-display');
                    if(subDisplay) {
                        subDisplay.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(price * itemDurationDays);
                    }
                }

                totalRoom += (price * itemDurationDays);
            });
        }

        // 2. Hitung Harga Service
        const serviceCheckboxes = document.querySelectorAll('input[name="services[]"]');
        serviceCheckboxes.forEach(cb => {
            if (cb.checked) {
                const price = parseFloat(cb.dataset.price) || 0;
                const id = cb.value;
                const qtyInput = document.getElementById('qty-' + id);
                const qty = qtyInput ? (parseInt(qtyInput.value) || 1) : 1;
                
                totalService += (price * qty);
            }
        });

        // 3. Update UI
        const grandTotal = totalRoom + totalService;
        const formatter = new Intl.NumberFormat('id-ID');

        if (grandTotalElement) {
            grandTotalElement.innerText = 'Rp' + formatter.format(grandTotal);
        }
        
        // Update rincian sub-total (Khusus Mode Villa)
        if (villaRoomTotalElement) villaRoomTotalElement.innerText = 'Rp' + formatter.format(totalRoom);
        if (villaServiceTotalElement) villaServiceTotalElement.innerText = 'Rp' + formatter.format(totalService);
    }

    // --- EVENT LISTENERS ---

    // Listen perubahan pada input durasi villa
    const villaInput = document.getElementById('villa_duration');
    if(villaInput) {
        villaInput.addEventListener('input', updateTotal);
        villaInput.addEventListener('change', updateTotal);
    }

    // Listen perubahan checkbox layanan (delegasi event)
    const services = document.querySelectorAll('input[name="services[]"]');
    services.forEach(s => s.addEventListener('change', updateTotal));

    // ======= NEW: Handlers untuk mode BIASA (per-kamar + / - dan unit-select) =======
    // Menangani tombol plus/minus per room, update .display-amount, .final-days-input, detail-text, subtotal-display
    // Perubahan penting:
    //  - final-days-input tetap menyimpan jumlah UNIT (mis. 2 jika memilih 2 Minggu) -> supaya server-side logic TETAP sama
    //  - perhitungan harga di UI (subtotal & grand total) memakai multiplier unit (1,7,30,365)
    document.querySelectorAll('.room-item').forEach(roomEl => {
        const btnPlus = roomEl.querySelector('.btn-plus');
        const btnMinus = roomEl.querySelector('.btn-minus');
        const displaySpan = roomEl.querySelector('.display-amount');
        const finalInput = roomEl.querySelector('.final-days-input');
        const detailText = roomEl.querySelector('.detail-text');
        const unitSelect = roomEl.querySelector('.unit-select');
        const subDisplay = roomEl.querySelector('.subtotal-display');

        // Helper untuk meng-update tampilan dan input
        function setUnits(newUnits) {
            // Jaga minimal 1 unit
            if (newUnits < 1) newUnits = 1;

            // Update tampilan unit count dan input yang dikirim ke server (jumlah unit)
            if (displaySpan) displaySpan.innerText = newUnits;
            if (finalInput) finalInput.value = newUnits;

            // Hitung multiplier berdasarkan unitSelect (1,7,30,365)
            const multiplier = unitSelect ? (parseInt(unitSelect.value) || 1) : 1;

            // Update detail text (mis. "2 Minggu")
            let unitLabel = 'Hari';
            if (unitSelect) {
                const u = unitSelect.value;
                if (u === '7') unitLabel = 'Minggu';
                else if (u === '30') unitLabel = 'Bulan';
                else if (u === '365') unitLabel = 'Tahun';
            }
            if (detailText) detailText.innerText = `${newUnits} ${unitLabel}`;

            // Update subtotal visible (harga_per_hari * jumlah_unit * multiplier)
            const price = parseFloat(roomEl.dataset.price) || 0;
            const subtotal = price * newUnits * multiplier;
            if (subDisplay) subDisplay.innerText = 'Rp' + new Intl.NumberFormat('id-ID').format(subtotal);

            // Recalculate totals (grand total)
            updateTotal();
        }

        // Attach click events
        if (btnPlus) {
            btnPlus.addEventListener('click', () => {
                const current = parseInt(displaySpan?.innerText || finalInput?.value || '1') || 1;
                setUnits(current + 1);
            });
        }
        if (btnMinus) {
            btnMinus.addEventListener('click', () => {
                const current = parseInt(displaySpan?.innerText || finalInput?.value || '1') || 1;
                setUnits(current - 1); // setUnits akan memastikan >=1
            });
        }

        // Jika user mengubah unit (Hari/Minggu/Bulan/Tahun), recalculate & refresh label & subtotal
        if (unitSelect) {
            unitSelect.addEventListener('change', () => {
                const current = parseInt(displaySpan?.innerText || finalInput?.value || '1') || 1;
                setUnits(current); // refresh subtotal & detail sesuai multiplier baru
            });
        }

        // Initial sync (agar saat load nilai 1 tercermin di detail & subtotal)
        (function initRoom() {
            const current = parseInt(displaySpan?.innerText || finalInput?.value || '1') || 1;
            setUnits(current);
        })();
    });
    // ======= END NEW Handlers =======

    // Panggil sekali saat load
    updateTotal();
});
</script>
@endsection
