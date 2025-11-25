{{-- resources/views/components/room-card-item.blade.php --}}
@props(['room', 'isAvailable'])

@php
    $roomId = $room->no_kamar;
    $bookingUrl = route('booking.checkout', ['kamar' => $roomId]);

    $cabang = $room->cabang;
    $detailUrl = route('cabang.kamar.show', [
        // Format URL slug manual (kecil semua, spasi jadi strip)
        'lokasi'   => str_replace(' ', '-', strtolower($cabang->lokasi)),
        'kategori' => str_replace(' ', '-', strtolower($cabang->kategori_cabang)),
        'no_kamar' => $roomId
    ]);
@endphp

<article
  id="card-{{ $roomId }}"
  class="room-card relative bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 select-none
  {{ $isAvailable
     ? 'cursor-pointer hover:shadow-lg group hover:border-green-200'
     : 'cursor-not-allowed opacity-75 grayscale bg-gray-50' }}"

  data-id="{{ $roomId }}"
  data-available="{{ $isAvailable ? '1' : '0' }}"
  data-booking-url="{{ $bookingUrl }}"

  {{-- Event hanya aktif jika tersedia --}}
  @if($isAvailable)
    onmousedown="startPress('{{ $roomId }}')"
    onmouseup="cancelPress('{{ $roomId }}')"
    mouseleave="cancelPress('{{ $roomId }}')"
    ontouchstart="startPress('{{ $roomId }}')"
    ontouchend="cancelPress('{{ $roomId }}')"
    ontouchmove="cancelPress('{{ $roomId }}')"
    onclick="handleCardClick('{{ $roomId }}', event)"
  @endif
>
    {{-- Checkbox (Hanya jika tersedia) --}}
    @if($isAvailable)
        <input type="checkbox" name="selected_rooms[]" value="{{ $roomId }}" id="check-{{ $roomId }}" class="hidden">

        {{-- Overlay Hijau --}}
        <div id="overlay-{{ $roomId }}" class="absolute inset-0 bg-green-600/10 z-20 hidden border-2 border-green-500 rounded-xl flex items-center justify-center pointer-events-none">
            <div class="bg-green-600 text-white rounded-full p-2 shadow-md transform scale-110">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            </div>
        </div>
    @endif

    {{-- Gambar Kamar --}}
    <div class="relative h-44 overflow-hidden bg-gray-100">
        <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}"
             class="w-full h-full object-cover transition-transform duration-500 {{ $isAvailable ? 'group-hover:scale-105' : '' }}"
             ondragstart="return false;">

        @if($isAvailable)
            <div id="loader-{{ $roomId }}" class="absolute bottom-0 left-0 h-1 bg-green-500 w-0 z-30"></div>
        @else
            {{-- Overlay Hitam untuk Dihuni --}}
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-10">
                <span class="bg-black/60 text-white px-3 py-1 rounded font-bold text-xs border border-white/20 uppercase tracking-wider">DIHUNI</span>
            </div>
        @endif

        {{-- Label Tipe di Pojok Atas --}}
        <div class="absolute top-2 right-2 bg-white/90 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide text-gray-600 shadow-sm z-20">
            {{ $room->tipe_kamar }}
        </div>
    </div>

    {{-- Info Bawah --}}
    <div class="p-4">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-xs text-gray-400 font-bold mb-0.5">NOMOR {{ $room->no_kamar }}</div>
                <div class="text-lg font-bold {{ $isAvailable ? 'text-gray-800 group-hover:text-green-700' : 'text-gray-500' }} transition-colors">
                    Rp {{ number_format($room->harga ?? $room->harga_kamar ?? 0, 0, ',', '.') }}
                </div>
            </div>

            {{-- Label Status --}}
            @if($isAvailable)
                <span class="bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded border border-green-100">Tersedia</span>
            @else
                <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded border border-red-100">Terisi</span>
            @endif
        </div>

        {{-- Tombol Detail --}}
        @if($isAvailable)
            <a href="{{ $detailUrl }}" onclick="event.stopPropagation()" class="detail-btn mt-4 block w-full text-center py-2 rounded-lg bg-gray-50 text-gray-600 font-bold text-xs hover:bg-green-600 hover:text-white transition-all border border-gray-100">
                LIHAT DETAIL
            </a>
        @else
            <button disabled class="mt-4 block w-full text-center py-2 rounded-lg bg-gray-100 text-gray-400 font-bold text-xs border border-gray-200 cursor-not-allowed">
                DETAIL
            </button>
        @endif
    </div>
</article>
