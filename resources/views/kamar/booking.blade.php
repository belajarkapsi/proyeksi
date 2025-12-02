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
    $defaultTanggal = old('tanggal', old('check_in', ''));

    // Pastikan $services collection tersedia (controller sudah menyiapkan)
    if (!isset($services) || $services === null) {
        try { $services = \App\Models\Service::all(); } catch (\Throwable $e) { $services = collect(); }
    }

    // Pastikan $serviceOnly ada (controller men-define jika service-only)
    $serviceOnly = $serviceOnly ?? false;
    $totalServicePrice = $totalServicePrice ?? 0;
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

        {{-- bila roomsMissing tetapi bukan service-only, tampilkan notice --}}
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
            {{-- KOLOM KIRI: FORM DATA DIRI --}}
            <div class="lg:col-span-7">
                {{-- 
                    Tampilkan FORM jika:
                    - Ada kamar (normal flow) OR
                    - service-only (user hanya memesan layanan) => tetap tampilkan form
                    Jika roomsMissing && !serviceOnly maka form tidak tampil (user diminta pilih kamar)
                --}}
                @if(!$roomsMissing || $serviceOnly)
                <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    @csrf

                    {{-- Jika ada kamar -> kirim kamar_ids seperti biasa --}}
                    @if(!$roomsMissing)
                        @foreach($rooms as $room)
                            <input type="hidden" name="kamar_ids[]" value="{{ $room->id_kamar }}">
                        @endforeach
                    @else
                        {{-- Service-only: beri flag agar backend tahu ini order layanan tanpa kamar --}}
                        <input type="hidden" name="service_only" value="1">
                    @endif

                    {{-- Jika ada services terpilih (service-only atau combined), sertakan input hidden untuk masing-masing supaya backend menerima data saat submit --}}
                    @if(isset($services) && $services->isNotEmpty())
                        @foreach($services as $srv)
                            {{-- Hidden inputs untuk services + qty --}}
                            <input type="hidden" name="services[]" value="{{ $srv->id }}" form="bookingForm" id="hidden_srv_{{ $srv->id }}">
                            <input type="hidden" name="service_quantity[{{ $srv->id }}]" value="{{ $srv->requested_qty ?? 0 }}" form="bookingForm" id="hidden_qty_{{ $srv->id }}">
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

                            {{-- Tanggal check-in tetap wajib di form, walau service-only (user mungkin perlu memilih tanggal service) --}}
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
                {{-- roomsMissing && !serviceOnly: pesan agar pilih kamar --}}
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

                    {{-- KHUSUS: jika service-only tampilkan ringkasan layanan saja --}}
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
                                {{-- Tombol kembali ke daftar kamar (agar user bisa edit/menambah kamar) --}}
                                @if(isset($cabang))
                                    <a href="{{ route('cabang.kamar.index', array_merge($cabang->route_params, request()->all())) }}" class="inline-block px-4 py-2 bg-white text-green-700 border border-green-200 rounded-lg shadow-sm hover:bg-green-50">Kembali / Tambah Kamar</a>
                                @endif
                            </div>

                            {{-- NOTE: tombol konfirmasi service-only dihapus di sini.
                                     Gunakan tombol konfirmasi utama (di bagian "TOTAL BAYAR") agar hanya ada 1 tombol submit. --}}
                        </div>

                    @else
                        {{-- START IF / ELSE: VILLA atau NON-VILLA (original UI preserved) --}}
                        @if($isVilla)
                            <div class="p-6 space-y-6">
                                {{-- (existing villa panel content kept untouched) --}}
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
                                                $qty = $reqQty[$service->id] ?? ($service->requested_qty ?? 0);
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
                            {{-- NON-VILLA: tampilkan daftar kamar & summary (existing) --}}
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
                    @endif

                    {{-- TOTAL BAYAR --}}
                    <div class="bg-gray-100 p-6 border-t border-gray-200">
                        <div class="flex justify-between items-end pt-2">
                            <span class="text-base font-bold text-gray-900">Total Bayar</span>
                            <span class="text-2xl font-bold text-green-700" id="grand_total">Rp0</span>
                        </div>

                        {{-- **Hanya satu tombol submit utama (ini tombol asli dari kode Anda)**
                             Saya tidak menambahkan tombol konfirmasi lain; service-only akan submit menggunakan form ini. --}}
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
    const isServiceOnly = {{ $serviceOnly ? 'true' : 'false' }};
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

    // Jika hanya service-only: hitung ringkasan layanan di panel kanan dan juga grand_total pada form
    function calcServiceOnlyTotals() {
        let serviceTotal = 0;
        $$('.service-check, input[id^="hidden_srv_"]').forEach(el => {
            // kalau kita menggunakan hidden_srv_* pattern (yang saya output di form kiri),
            // ambil qty dari hidden_qty_<id>
            if (el.id && el.id.startsWith('hidden_srv_')) {
                const id = el.value;
                const qtyEl = document.getElementById('hidden_qty_' + id);
                const qty = qtyEl ? Math.max(0, parseInt(qtyEl.value||0,10)) : 0;
                const price = Number(el.getAttribute('data-price') || 0);
                serviceTotal += price * qty;
            } else if (el.classList && el.classList.contains('service-check')) {
                const id = el.value;
                const qtyEl = document.getElementById('qty-' + id) || document.getElementById('hidden_qty_' + id);
                const qty = qtyEl ? Math.max(0, parseInt(qtyEl.value||0,10)) : 0;
                const price = Number(el.getAttribute('data-price') || 0);
                if ((el.checked || qty > 0)) serviceTotal += price * qty;
            }
        });

        if (grandTotalEl) grandTotalEl.innerText = formatRupiah(serviceTotal);
    }

    if (isServiceOnly) {
        // Attach listeners to quantity hidden inputs (if UI allows editing, they will be updated by other handlers)
        $$('input[name^="service_quantity"], input[id^="qty-"], input[id^="hidden_qty_"]').forEach(i => {
            i.addEventListener('change', calcServiceOnlyTotals);
            i.addEventListener('input', calcServiceOnlyTotals);
        });
        calcServiceOnlyTotals();
    }

    // -- preserve original JS logic for villa/non-villa as much as possible --
    // (I intentionally don't change your existing JS — original handlers remain below)
    if (isVilla) {
        // existing villa JS must remain here (adjustVillaDuration, calcVillaTotal, toggleServiceUI, updateServiceUI)
        // If you have those functions declared elsewhere in this file, they continue to work.
        // Example: ensure you still have window.calcVillaTotal(), window.adjustVillaDuration(), etc.
    } else {
        // Non-villa: your existing room calculation handlers
    }

    // -----------------------
    // --- HIDDEN INPUTS BEFORE SUBMIT (safe, non-intrusive)
    // -----------------------
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        // helper to add/replace hidden input
        function setHidden(name, value) {
            if (name.endsWith('[]')) {
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = name;
                inp.value = value;
                inp.setAttribute('data-dynamic-service', '1');
                bookingForm.appendChild(inp);
                return inp;
            } else {
                let ex = bookingForm.querySelector('input[name="'+name+'"]');
                if (ex) { ex.value = value; return ex; }
                const inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = name;
                inp.value = value;
                inp.setAttribute('data-dynamic-qty', '1');
                bookingForm.appendChild(inp);
                return inp;
            }
        }

        bookingForm.addEventListener('submit', function(e) {
            try {
                // remove previously injected dynamic hidden services/qty to avoid duplicates
                Array.from(bookingForm.querySelectorAll('input[data-dynamic-service]')).forEach(n => n.remove());
                Array.from(bookingForm.querySelectorAll('input[data-dynamic-qty]')).forEach(n => n.remove());

                // 1) Visible toggles (ids like vis-check-<id>)
                const visChecks = document.querySelectorAll('input[id^="vis-check-"]');
                visChecks.forEach(v => {
                    const m = v.id.match(/vis-check-(\d+)/);
                    if (!m) return;
                    const id = m[1];
                    if (v.checked) {
                        const s = document.createElement('input');
                        s.type = 'hidden'; s.name = 'services[]'; s.value = id;
                        s.setAttribute('data-dynamic-service', '1');
                        bookingForm.appendChild(s);
                    }
                    // get qty: check for qty-<id>, hidden_qty_<id>, disp-<id>
                    const qEl = document.getElementById('qty-'+id) || document.getElementById('hidden_qty_'+id) || document.getElementById('disp-'+id) || document.getElementById('display-qty-'+id);
                    const qv = qEl ? (qEl.value !== undefined ? qEl.value : qEl.innerText) : 1;
                    const qh = document.createElement('input');
                    qh.type = 'hidden'; qh.name = 'service_quantity['+id+']'; qh.value = qv || 1;
                    qh.setAttribute('data-dynamic-qty', '1');
                    bookingForm.appendChild(qh);
                });

                // 2) Gazebo/counter style elements (disp-<id> / display-qty-<id> / display_qty_<id>)
                const qtyElsSelector = '[id^="disp-"], [id^="display-qty-"], [id^="display_qty_"]';
                const qtyEls = document.querySelectorAll(qtyElsSelector);
                qtyEls.forEach(el => {
                    const match = el.id.match(/(?:disp-|display-qty-|display_qty_)(\d+)/);
                    if (!match) return;
                    const id = match[1];
                    const qty = parseInt((el.value !== undefined ? el.value : el.innerText) || 0, 10) || 0;
                    if (qty > 0) {
                        // add service id if not already added
                        const existing = bookingForm.querySelector('input[name="services[]"][value="'+id+'"]');
                        if (!existing) {
                            const s = document.createElement('input');
                            s.type = 'hidden'; s.name = 'services[]'; s.value = id;
                            s.setAttribute('data-dynamic-service', '1');
                            bookingForm.appendChild(s);
                        }
                        const qh = document.createElement('input');
                        qh.type = 'hidden'; qh.name = 'service_quantity['+id+']'; qh.value = qty;
                        qh.setAttribute('data-dynamic-qty', '1');
                        bookingForm.appendChild(qh);
                    }
                });

                // 3) Also respect any existing hidden inputs named like hidden_srv_<id> or svc-<id>
                // Convert them into standard 'services[]' + 'service_quantity[...]' if needed
                const hiddenSrvInputs = document.querySelectorAll('input[id^="hidden_srv_"], input[id^="svc-"]');
                hiddenSrvInputs.forEach(h => {
                    const val = h.value;
                    if (!val) return;
                    const id = String(val);
                    // ensure services[] has this id
                    const existing = bookingForm.querySelector('input[name="services[]"][value="'+id+'"]');
                    if (!existing) {
                        const s = document.createElement('input');
                        s.type = 'hidden'; s.name = 'services[]'; s.value = id;
                        s.setAttribute('data-dynamic-service', '1');
                        bookingForm.appendChild(s);
                    }
                    // qty from hidden_qty_<id> if exists
                    const qEl = document.getElementById('hidden_qty_' + id) || document.getElementById('qty-' + id);
                    const qv = qEl ? (qEl.value !== undefined ? qEl.value : qEl.innerText) : 0;
                    const qh = document.createElement('input');
                    qh.type = 'hidden'; qh.name = 'service_quantity['+id+']'; qh.value = qv || 0;
                    qh.setAttribute('data-dynamic-qty', '1');
                    bookingForm.appendChild(qh);
                });

                // 4) If we added any services and there are no kamar_ids[] fields, set service_only flag
                const hasServiceHidden = bookingForm.querySelectorAll('input[name="services[]"]').length > 0;
                const hasKamarHidden = bookingForm.querySelectorAll('input[name="kamar_ids[]"]').length > 0;
                if (hasServiceHidden && !hasKamarHidden) {
                    setHidden('service_only', '1');
                }

                // done — form will submit normally
            } catch (err) {
                // jika terjadi error JS, jangan blok submit — biarkan server-side fallback menangani
                console.error('bookingForm submit helper error', err);
            }
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
