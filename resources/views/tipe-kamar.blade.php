@extends('layout.master')
@section('title', 'Daftar Kamar')

@section('content')
<div class="container mx-auto px-6 py-8 relative min-h-screen">
  
  {{-- Breadcrumb --}}
  <nav class="text-sm text-gray-600 mb-6">
    <a href="#" class="mr-4 hover:underline">Location</a>
    <span class="mr-4">Pondok Siti Hajar</span>
    <span class="text-gray-400">Ekonomis</span>
  </nav>

  {{-- TOMBOL BATAL (Floating Top Center - Muncul saat mode seleksi) --}}
  <button id="top-cancel-btn" type="button" onclick="exitSelectionMode()" 
    class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 hidden bg-white text-gray-800 border border-gray-200 shadow-xl px-6 py-2 rounded-full font-semibold text-sm hover:bg-gray-50 transition-all items-center gap-2 ring-2 ring-white/50 animate-bounce-once">
    <span class="text-red-500 bg-red-100 p-1 rounded-full">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </span>
    <span>Batalkan Pilihan</span>
  </button>

  {{-- FORM WRAPPER (PENTING: Action mengarah ke booking.checkout) --}}
  <form id="bookingForm" action="{{ route('booking.checkout') }}" method="POST">
    @csrf
    
    {{-- GRID CARD KAMAR --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 select-none">
      @foreach($rooms as $room)
        @php
            // Logic ketersediaan (sesuaikan dengan kolom DB Anda)
            $isAvailable = $room->available ?? true;
            $roomId = $room->no_kamar;

            // PENTING: URL untuk Single Click (Langsung ke Checkout)
            // Ini akan menghasilkan link seperti: /booking/checkout?kamar=101
            $bookingUrl = route('booking.checkout', ['kamar' => $roomId]);

            // URL untuk Tombol Detail (Ke Halaman Detail)
            $detailUrl = route('kamar.detail', ['no_kamar' => $roomId]); 
        @endphp

        <article 
          id="card-{{ $roomId }}"
          class="room-card relative bg-white rounded-xl shadow-lg p-4 overflow-hidden transition-all duration-300 border-2 border-transparent cursor-pointer group"
          
          {{-- DATA ATTRIBUTES (Digunakan oleh JavaScript) --}}
          data-id="{{ $roomId }}"
          data-available="{{ $isAvailable }}"
          data-booking-url="{{ $bookingUrl }}" 
          
          {{-- EVENT HANDLERS (Untuk Long Press & Click) --}}
          onmousedown="startPress('{{ $roomId }}')" 
          onmouseup="cancelPress('{{ $roomId }}')" 
          mouseleave="cancelPress('{{ $roomId }}')"
          ontouchstart="startPress('{{ $roomId }}')" 
          ontouchend="cancelPress('{{ $roomId }}')"
          ontouchmove="cancelPress('{{ $roomId }}')"
          onclick="handleCardClick('{{ $roomId }}', event)"
        >
          {{-- Input Checkbox Hidden (Untuk Form Multi Select) --}}
          <input type="checkbox" name="selected_rooms[]" value="{{ $roomId }}" id="check-{{ $roomId }}" class="hidden">

          {{-- Overlay Hijau (Muncul saat Terpilih) --}}
          <div id="overlay-{{ $roomId }}" class="absolute inset-0 bg-green-600/10 z-10 hidden border-2 border-green-600 rounded-xl flex items-center justify-center pointer-events-none">
             <div class="absolute top-3 right-3 bg-green-600 text-white rounded-full p-1.5 shadow-md transform scale-110">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
             </div>
          </div>

          {{-- Gambar Kamar --}}
          <div class="rounded-lg overflow-hidden relative">
            <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}"
                 alt="Kamar {{ $room->no_kamar }}"
                 ondragstart="return false;" 
                 class="w-full h-44 object-cover rounded-md transition-transform duration-500 group-hover:scale-105">
            
            {{-- Loader Progress Bar (Indikator Tekan Lama) --}}
            <div id="loader-{{ $roomId }}" class="absolute bottom-0 left-0 h-1.5 bg-green-500 w-0 transition-all ease-linear z-20"></div>
          </div>

          {{-- Badge Nomor & Lantai --}}
          <div class="absolute left-6 top-36 transform -translate-y-1/2 z-20 pointer-events-none">
            <div class="flex items-center space-x-2">
              <div class="bg-green-600 text-white font-extrabold px-3 py-1 rounded-md text-lg shadow-sm">
                {{ $room->no_kamar }}
              </div>
              <div class="bg-white/90 text-green-700 text-xs px-2 py-0.5 rounded-full border border-green-100 shadow-sm">
                Lantai {{ $room->floor ?? '1' }}
              </div>
            </div>
          </div>

          {{-- Detail Teks --}}
          <div class="mt-4 text-sm text-gray-700 relative z-0">
            <div class="flex items-start justify-between">
              <div>
                <p class="text-xs text-gray-500">Harga :</p>
                {{-- Pastikan kolom harga ada di DB --}}
                <p class="text-sm font-bold text-green-700">Rp {{ number_format($room->harga ?? 0, 0, ',', '.') }}</p>
              </div>
              <div class="text-right">
                @if( $isAvailable )
                  <span class="inline-block text-xs bg-green-50 text-green-700 px-3 py-1 rounded-full border border-green-100">Tersedia</span>
                @else
                  <span class="inline-block text-xs bg-red-50 text-red-600 px-3 py-1 rounded-full border border-red-100">Terisi</span>
                @endif
              </div>
            </div>

            <div class="mt-3 text-xs text-gray-600 space-y-1">
              <p>Tempat Tidur : {{ $room->bed_type ?? 'Bed 160' }}</p>
              <p>Kapasitas : Max. {{ $room->capacity ?? 2 }} Orang</p>
            </div>

            {{-- TOMBOL DETAIL (Pojok Kanan Bawah) --}}
            <div class="mt-4 text-right relative z-30">
              @if( $isAvailable )
                  {{-- onclick="event.stopPropagation()" mencegah trigger ke kartu --}}
                  <a href="{{ $detailUrl }}"
                     onclick="event.stopPropagation()"
                     class="detail-btn inline-block bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-md shadow transition-colors">
                     Detail
                  </a>
              @else
                 <button disabled class="inline-block bg-gray-100 text-gray-400 text-sm font-semibold px-4 py-2 rounded-md border border-gray-200 cursor-not-allowed">
                   Detail
                 </button>
              @endif
            </div>
          </div>
        </article>
      @endforeach

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
@endsection