@extends('layout.master')
@section('title', isset($cabang) ? 'Daftar Kamar - ' . $cabang->nama_cabang : 'Semua Daftar Kamar')

@section('content')

{{-- 1. LOGIKA PEMISAHAN DATA (PHP DI VIEW) --}}
@php
    // Jika data dipaginate, ambil items-nya saja untuk difilter manual di view
    // (Note: Ini idealnya untuk data < 100 items. Jika ribuan, filter harus di controller/query)
    $allRooms = $rooms instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rooms->items() : $rooms;
    $allRooms = collect($allRooms);

    // Filter Kategori Berdasarkan String Tipe Kamar
    // Tersedia & Ekonomis
    $ekonomis = $allRooms->filter(function($r) {
        $status = strtolower(trim($r->status_kamar ?? $r->status));
        return (stripos($status, 'sedia') !== false) && (stripos($r->tipe_kamar, 'Ekonomis') !== false);
    });

    // Tersedia & Standard
    $standard = $allRooms->filter(function($r) {
        $status = strtolower(trim($r->status_kamar ?? $r->status));
        return (stripos($status, 'sedia') !== false) && (stripos($r->tipe_kamar, 'Standar') !== false);
    });

    // Terisi / Maintenance (Apapun tipenya)
    $occupied = $allRooms->filter(function($r) {
        $status = strtolower(trim($r->status_kamar ?? $r->status));
        return stripos($status, 'sedia') === false;
    });
@endphp

<div class="container mx-auto px-6 py-8 relative min-h-screen">

  {{-- 2. BREADCRUMB & NAVIGATION --}}
  <nav class="text-sm text-gray-600 mb-6 flex items-center gap-2">
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

  {{-- STICKY FILTER NAV --}}
  <div class="sticky top-20 z-30 bg-white/95 backdrop-blur border-b border-gray-100 py-3 mb-8 -mx-6 px-6 md:mx-0 md:px-0 md:rounded-xl shadow-sm">
    <div class="flex items-center gap-3 overflow-x-auto no-scrollbar">
        <span class="text-xs font-bold text-gray-500 uppercase tracking-wide mr-2">Lompat ke:</span>

        @if($ekonomis->count() > 0)
            <a href="#ekonomis" class="px-4 py-2 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200 hover:bg-green-100 transition">
                Ekonomis ({{ $ekonomis->count() }})
            </a>
        @endif

        @if($standard->count() > 0)
            <a href="#standard" class="px-4 py-2 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100 transition">
                Standard ({{ $standard->count() }})
            </a>
        @endif

        @if($occupied->count() > 0)
            <a href="#occupied" class="px-4 py-2 rounded-full text-xs font-bold bg-gray-100 text-gray-600 border border-gray-300 hover:bg-gray-200 transition">
                Terisi ({{ $occupied->count() }})
            </a>
        @endif
    </div>
  </div>

  {{-- 3. TOMBOL BATAL (Floating) --}}
  <button id="top-cancel-btn" onclick="exitSelectionMode()" class="fixed top-36 left-1/2 transform -translate-x-1/2 z-50 hidden items-center gap-2 bg-red-500 text-white px-6 py-2.5 rounded-full shadow-xl animate-bounce-once ring-4 ring-white/50 hover:bg-red-600 transition-transform active:scale-95 cursor-pointer">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    <span class="text-xs md:text-sm font-bold whitespace-nowrap">Batalkan Pilihan</span>
  </button>

  {{-- 4. FORM BOOKING (Grid Kamar) --}}
  <form id="bookingForm" action="{{ route('booking.checkout') }}" method="POST">
    @csrf
    <div class="space-y-16">

        {{-- SECTION A: EKONOMIS --}}
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

        {{-- SECTION B: STANDARD --}}
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

        {{-- SECTION C: TERISI --}}
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

        {{-- JIKA KOSONG SEMUA --}}
        @if($ekonomis->isEmpty() && $standard->isEmpty() && $occupied->isEmpty())
            <div class="text-center py-20">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Belum Ada Data Kamar</h3>
                <p class="text-gray-500">Silakan hubungi admin cabang ini.</p>
            </div>
        @endif

    </div>

    {{-- FLOATING ACTION BAR --}}
    <div id="floating-bar" class="fixed bottom-6 inset-x-0 flex justify-center items-end transform translate-y-32 transition-transform duration-300 z-40 pointer-events-none">
      <div class="bg-white border border-gray-200 rounded-2xl shadow-2xl px-2 py-2 pointer-events-auto flex items-center gap-2">

        {{-- Tombol Batal --}}
        <button type="button" onclick="exitSelectionMode()" class="px-4 py-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl text-sm font-semibold transition-colors">
          Batal
        </button>

        {{-- Tombol Submit Multi Select --}}
        <button type="submit" class="relative bg-green-700 hover:bg-green-800 text-white px-6 py-3 rounded-xl shadow-lg font-bold text-base flex items-center transition-colors">
          <span>Pesan (<span id="text-count-btn">0</span>)</span>
          <span id="selected-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow border-2 border-white min-w-[24px] text-center">
            0
          </span>
        </button>
      </div>
    </div>

  </form>
</div>

@endsection

{{-- JAVASCRIPT VANILLA --}}
<script>
  let pressTimer;
  let isSelectionMode = false;
  const longPressDuration = 500; // 0.5 Detik

  // 1. START TIMER (Saat ditekan)
  function startPress(id) {
    const card = document.getElementById('card-' + id);
    const loader = document.getElementById('loader-' + id);
    const isAvailable = card.getAttribute('data-available');

    // Jangan jalankan jika kamar penuh atau sudah mode seleksi
    if (isAvailable != "1" || isSelectionMode) return;

    // Animasi Loader
    if(loader) {
        loader.style.width = '100%';
        loader.style.transitionDuration = '0.5s';
    }

    pressTimer = setTimeout(() => {
      enterSelectionMode(id);
    }, longPressDuration);
  }

  // 2. CANCEL TIMER (Saat dilepas/digeser)
  function cancelPress(id) {
    const loader = document.getElementById('loader-' + id);
    if(loader) {
        loader.style.width = '0';
        loader.style.transitionDuration = '0.2s';
    }
    clearTimeout(pressTimer);
  }

  // 3. HANDLE CARD CLICK (Logika Utama)
  function handleCardClick(id, event) {
    const card = document.getElementById('card-' + id);
    const isAvailable = card.getAttribute('data-available');
    const bookingUrl = card.getAttribute('data-booking-url');

    // Jika tombol Detail diklik, hentikan fungsi ini (biarkan href detail bekerja)
    if (event.target.closest('.detail-btn')) return;

    if (isAvailable != "1") return;

    // KONDISI A: Mode Seleksi AKTIF -> Lakukan Toggle (Pilih/Hapus)
    if (isSelectionMode) {
      event.preventDefault();
      toggleSelection(id);
    }
    // KONDISI B: Mode Normal -> Langsung Pindah ke Halaman Checkout (1 Kamar)
    else {
      window.location.href = bookingUrl;
    }
  }

  // 4. Masuk Mode Seleksi
  function enterSelectionMode(initialId) {
    isSelectionMode = true;
    // Haptic feedback (getar) di Android
    if (navigator.vibrate) navigator.vibrate(50);

    toggleSelection(initialId);
    showUI();
  }

  // 5. Toggle Visual & Checkbox
  function toggleSelection(id) {
    const checkbox = document.getElementById('check-' + id);
    const overlay = document.getElementById('overlay-' + id);
    const card = document.getElementById('card-' + id);

    checkbox.checked = !checkbox.checked;

    if (checkbox.checked) {
      overlay.classList.remove('hidden');
      card.classList.add('ring-2', 'ring-green-600', 'scale-[0.98]');
    } else {
      overlay.classList.add('hidden');
      card.classList.remove('ring-2', 'ring-green-600', 'scale-[0.98]');
    }
    updateCounter();
  }

  // 6. Update Angka Pesanan
  function updateCounter() {
    const checkedCount = document.querySelectorAll('input[name="selected_rooms[]"]:checked').length;
    document.getElementById('selected-count').innerText = checkedCount;
    document.getElementById('text-count-btn').innerText = checkedCount;

    // Jika tidak ada yang dipilih, keluar mode seleksi
    if (checkedCount === 0) {
       exitSelectionMode();
    }
  }

  // 7. Keluar Mode Seleksi
  function exitSelectionMode() {
    isSelectionMode = false;
    document.querySelectorAll('input[name="selected_rooms[]"]').forEach(el => el.checked = false);

    // Reset Tampilan
    document.querySelectorAll('.room-card').forEach(el => {
        el.classList.remove('ring-2', 'ring-green-600', 'scale-[0.98]');
    });
    document.querySelectorAll('[id^="overlay-"]').forEach(el => el.classList.add('hidden'));

    hideUI();
  }

  // 8. UI Helpers (Tampilkan/Sembunyikan Bar)
  function showUI() {
    document.getElementById('floating-bar').classList.remove('translate-y-32');
    document.getElementById('top-cancel-btn').classList.remove('hidden');
    document.getElementById('top-cancel-btn').classList.add('flex');
  }

  function hideUI() {
    document.getElementById('floating-bar').classList.add('translate-y-32');
    document.getElementById('top-cancel-btn').classList.add('hidden');
    document.getElementById('top-cancel-btn').classList.remove('flex');
  }
</script>

