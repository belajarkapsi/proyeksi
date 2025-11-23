@extends('layout.master')
@section('title', 'Daftar Kamar')

@section('content')

{{-- 1. LOGIKA FILTER DATA (PHP) --}}
@php
    $allData = $rooms instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rooms->items() : $rooms;
    $allRooms = collect($allData);

    // Filter Kategori
    $ekonomis = $allRooms->filter(fn($r) => strtolower(trim($r->status)) === 'tersedia' && stripos(strtolower($r->tipe_kamar), 'ekonomis') !== false);
    $standard = $allRooms->filter(fn($r) => strtolower(trim($r->status)) === 'tersedia' && stripos(strtolower($r->tipe_kamar), 'ekonomis') === false);
    $occupied = $allRooms->filter(fn($r) => strtolower(trim($r->status)) !== 'tersedia');
@endphp

    {{-- 2. STICKY CATEGORY NAVIGATION --}}
    {{-- top-20 disesuaikan dengan tinggi navbar layout.master (biasanya h-16 atau h-20) --}}
    <div class="sticky top-16 lg:top-20 z-30 bg-gray-50/95 backdrop-blur-md border-b border-gray-200 shadow-sm transition-all duration-300">
        <div class="container mx-auto px-4 py-3">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                
                {{-- Tombol Kembali --}}
                <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-green-700 transition-colors group w-fit">
                     <div class="p-1.5 bg-white rounded-full border border-gray-200 shadow-sm group-hover:border-green-500 transition-colors">
                        <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                     </div>
                     <span>Kembali</span>
                </a>

                {{-- Filter Tabs --}}
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1 md:pb-0">
                    <a href="#ekonomis" class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide bg-white text-green-700 border border-green-200 hover:bg-green-50 shadow-sm transition-all focus:ring-2 focus:ring-green-500 whitespace-nowrap">
                        Ekonomis <span class="ml-1 bg-green-100 px-1.5 py-0.5 rounded-md text-[10px]">{{ $ekonomis->count() }}</span>
                    </a>
                    <a href="#standard" class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide bg-white text-blue-700 border border-blue-200 hover:bg-blue-50 shadow-sm transition-all focus:ring-2 focus:ring-blue-500 whitespace-nowrap">
                        Standard <span class="ml-1 bg-blue-100 px-1.5 py-0.5 rounded-md text-[10px]">{{ $standard->count() }}</span>
                    </a>
                    <a href="#occupied" class="flex-shrink-0 px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide bg-white text-gray-500 border border-gray-200 hover:bg-gray-100 shadow-sm transition-all focus:ring-2 focus:ring-gray-400 whitespace-nowrap">
                        Terisi <span class="ml-1 bg-gray-100 px-1.5 py-0.5 rounded-md text-[10px]">{{ $occupied->count() }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. TOMBOL BATAL PILIHAN (Floating Top) --}}
    <button id="top-cancel-btn" onclick="exitSelectionMode()" class="fixed top-36 lg:top-40 left-1/2 transform -translate-x-1/2 z-50 hidden items-center gap-2 bg-red-500 text-white px-6 py-2.5 rounded-full shadow-xl animate-bounce-once ring-4 ring-white/50 hover:bg-red-600 transition-transform active:scale-95 cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span class="text-xs md:text-sm font-bold whitespace-nowrap">Batalkan Pilihan</span>
    </button>

    {{-- 4. FORM BOOKING UTAMA --}}
    <form id="bookingForm" action="{{ route('booking.checkout') }}" method="POST">
        @csrf
        <div class="container mx-auto px-4 py-8 space-y-16">
            
            {{-- === SECTION A: EKONOMIS === --}}
            {{-- scroll-mt-48 agar judul tidak ketutup navbar saat discroll --}}
            <section id="ekonomis" class="scroll-mt-48">
                <div class="flex items-center gap-3 mb-6 pl-2 border-l-4 border-green-500">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Tipe Ekonomis</h2>
                </div>

                @if($ekonomis->isEmpty())
                    <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl p-10 text-center text-gray-400">
                        <p class="font-medium">Tidak ada kamar ekonomis tersedia.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($ekonomis as $room)
                            {{-- START CARD EKONOMIS --}}
                            <div id="card-{{ $room->no_kamar }}" 
                                class="room-card group relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 cursor-pointer"
                                data-id="{{ $room->no_kamar }}" 
                                data-available="1"
                                data-booking-url="{{ route('booking.checkout', ['kamar' => $room->no_kamar]) }}"
                                onmousedown="startPress('{{ $room->no_kamar }}')" onmouseup="cancelPress('{{ $room->no_kamar }}')" onmouseleave="cancelPress('{{ $room->no_kamar }}')"
                                ontouchstart="startPress('{{ $room->no_kamar }}')" ontouchend="cancelPress('{{ $room->no_kamar }}')" ontouchmove="cancelPress('{{ $room->no_kamar }}')"
                                onclick="handleCardClick('{{ $room->no_kamar }}', event)">
                                
                                {{-- Checkbox Hidden --}}
                                <input type="checkbox" name="selected_rooms[]" value="{{ $room->no_kamar }}" id="check-{{ $room->no_kamar }}" class="hidden">
                                
                                {{-- Overlay Hijau --}}
                                <div id="overlay-{{ $room->no_kamar }}" class="absolute inset-0 bg-green-600/20 z-30 hidden border-[3px] border-green-600 rounded-2xl flex items-center justify-center pointer-events-none">
                                    <div class="bg-green-600 text-white rounded-full p-2 shadow-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                                </div>

                                {{-- Gambar --}}
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" ondragstart="return false;">
                                    {{-- Loader Bar --}}
                                    <div id="loader-{{ $room->no_kamar }}" class="absolute bottom-0 left-0 h-1.5 bg-green-500 w-0 z-20"></div>
                                    {{-- Badge Nomor --}}
                                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-sm font-bold text-gray-800 shadow-sm z-10">
                                        No. {{ $room->no_kamar }}
                                    </div>
                                </div>

                                {{-- Info --}}
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Ekonomis</p>
                                            <p class="text-lg font-bold text-green-700">Rp {{ number_format($room->harga ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Tersedia</span>
                                    </div>
                                    
                                    {{-- Tombol Detail (Link A) --}}
                                    <a href="{{ route('kamar.detail', ['no_kamar' => $room->no_kamar]) }}" class="detail-btn mt-4 block w-full text-center py-2.5 rounded-lg bg-gray-50 text-green-700 font-bold text-sm hover:bg-green-600 hover:text-white transition-colors border border-gray-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            {{-- END CARD EKONOMIS --}}
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- === SECTION B: STANDARD === --}}
            <section id="standard" class="scroll-mt-48">
                <div class="flex items-center gap-3 mb-6 pl-2 border-l-4 border-blue-500">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800">Tipe Standard</h2>
                </div>

                @if($standard->isEmpty())
                    <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl p-10 text-center text-gray-400">
                        <p class="font-medium">Tidak ada kamar standard tersedia.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($standard as $room)
                            {{-- START CARD STANDARD --}}
                            <div id="card-{{ $room->no_kamar }}" 
                                class="room-card group relative bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 cursor-pointer hover:border-blue-200"
                                data-id="{{ $room->no_kamar }}" 
                                data-available="1"
                                data-booking-url="{{ route('booking.checkout', ['kamar' => $room->no_kamar]) }}"
                                onmousedown="startPress('{{ $room->no_kamar }}')" onmouseup="cancelPress('{{ $room->no_kamar }}')" onmouseleave="cancelPress('{{ $room->no_kamar }}')"
                                ontouchstart="startPress('{{ $room->no_kamar }}')" ontouchend="cancelPress('{{ $room->no_kamar }}')" ontouchmove="cancelPress('{{ $room->no_kamar }}')"
                                onclick="handleCardClick('{{ $room->no_kamar }}', event)">
                                
                                <input type="checkbox" name="selected_rooms[]" value="{{ $room->no_kamar }}" id="check-{{ $room->no_kamar }}" class="hidden">
                                
                                <div id="overlay-{{ $room->no_kamar }}" class="absolute inset-0 bg-green-600/20 z-30 hidden border-[3px] border-green-600 rounded-2xl flex items-center justify-center pointer-events-none">
                                    <div class="bg-green-600 text-white rounded-full p-2 shadow-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                                </div>

                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" ondragstart="return false;">
                                    <div id="loader-{{ $room->no_kamar }}" class="absolute bottom-0 left-0 h-1.5 bg-blue-500 w-0 z-20"></div>
                                    <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur px-3 py-1 rounded-lg text-sm font-bold text-gray-800 shadow-sm z-10">
                                        No. {{ $room->no_kamar }}
                                    </div>
                                </div>

                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Standard</p>
                                            <p class="text-lg font-bold text-blue-700">Rp {{ number_format($room->harga ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded uppercase">Tersedia</span>
                                    </div>
                                    
                                    <a href="{{ route('kamar.detail', ['no_kamar' => $room->no_kamar]) }}" class="detail-btn mt-4 block w-full text-center py-2.5 rounded-lg bg-gray-50 text-blue-600 font-bold text-sm hover:bg-blue-600 hover:text-white transition-colors border border-gray-100">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                            {{-- END CARD STANDARD --}}
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- === SECTION C: TERISI / MAINTENANCE === --}}
            <section id="occupied" class="scroll-mt-48 bg-gray-100/80 rounded-3xl p-6 border border-gray-200">
                <div class="flex items-center gap-3 mb-6 opacity-60">
                    <div class="w-1.5 h-8 bg-gray-500 rounded-full"></div>
                    <h2 class="text-xl font-bold text-gray-700">Terisi / Maintenance</h2>
                </div>

                @if($occupied->isEmpty())
                    <div class="text-center text-gray-400 py-4">Semua kamar tersedia (Kosong).</div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 opacity-90">
                        @foreach($occupied as $room)
                            {{-- START CARD OCCUPIED --}}
                            <div class="relative bg-white rounded-2xl shadow-none border border-gray-200 overflow-hidden opacity-70 grayscale filter cursor-not-allowed hover:opacity-100 transition-opacity">
                                <div class="relative h-48 bg-gray-300">
                                    <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="w-full h-full object-cover opacity-50" ondragstart="return false;">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="bg-black/60 text-white px-4 py-1.5 rounded-lg font-bold text-sm tracking-wide border border-white/20">DIHUNI</span>
                                    </div>
                                    <div class="absolute bottom-3 left-3 bg-white/80 px-3 py-1 rounded-lg text-sm font-bold text-gray-600">
                                        No. {{ $room->no_kamar }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase">{{ $room->tipe_kamar }}</p>
                                            <p class="text-lg font-bold text-gray-500">Rp {{ number_format($room->harga ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="bg-gray-200 text-gray-500 text-[10px] font-bold px-2 py-1 rounded uppercase">Terisi</span>
                                    </div>
                                    <button type="button" disabled class="mt-4 w-full block text-center py-2.5 rounded-lg bg-gray-100 text-gray-400 font-semibold text-sm cursor-not-allowed">
                                        Detail
                                    </button>
                                </div>
                            </div>
                            {{-- END CARD OCCUPIED --}}
                        @endforeach
                    </div>
                @endif
            </section>

        </div>

              {{-- SKELETON LOADER (Jika data kosong) --}}
      @if($rooms->isEmpty())
        @for($i=0;$i<6;$i++)
          <div class="bg-white rounded-xl shadow-lg p-4 animate-pulse">
            <div class="rounded-lg overflow-hidden bg-gray-200 h-44 mb-4"></div>
            <div class="h-8 bg-gray-200 rounded w-1/3 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-1/2 mb-1"></div>
          </div>
        @endfor
      @endif
    </div>

    {{-- FLOATING BAR (Muncul saat Mode Seleksi) --}}
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

