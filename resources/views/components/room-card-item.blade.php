{{-- TEMPLATE HTML KARTU (Untuk referensi copy-paste ke loop di atas) --}}
{{-- 
<article id="card-{{ $room->no_kamar }}" 
    class="room-card group relative bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg cursor-pointer select-none"
    data-booking-url="{{ route('booking.checkout', ['kamar' => $room->no_kamar]) }}"
    onmousedown="startPress('{{ $room->no_kamar }}')"
    onmouseup="cancelPress('{{ $room->no_kamar }}')"
    mouseleave="cancelPress('{{ $room->no_kamar }}')"
    ontouchstart="startPress('{{ $room->no_kamar }}')"
    ontouchend="cancelPress('{{ $room->no_kamar }}')"
    ontouchmove="cancelPress('{{ $room->no_kamar }}')"
    onclick="handleCardClick('{{ $room->no_kamar }}', event)">

    <input type="checkbox" name="selected_rooms[]" value="{{ $room->no_kamar }}" id="check-{{ $room->no_kamar }}" class="hidden">

    <div id="overlay-{{ $room->no_kamar }}" class="absolute inset-0 bg-green-600/10 z-20 hidden border-2 border-green-500 rounded-xl flex items-center justify-center pointer-events-none">
        <div class="bg-green-600 text-white rounded-full p-2 shadow-md transform scale-110">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
        </div>
    </div>

    <div class="relative h-44 overflow-hidden bg-gray-100">
        <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" ondragstart="return false;">
        <div id="loader-{{ $room->no_kamar }}" class="absolute bottom-0 left-0 h-1 bg-green-500 w-0 z-30"></div>
        <div class="absolute top-2 right-2 bg-white/90 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide text-gray-600 shadow-sm">
            {{ $type == 'ekonomis' ? 'Ekonomis' : 'Standard' }}
        </div>
    </div>

    <div class="p-4">
        <div class="flex justify-between items-start">
            <div>
                <div class="text-xs text-gray-400 font-bold mb-0.5">NOMOR {{ $room->no_kamar }}</div>
                <div class="text-lg font-bold text-gray-800 group-hover:text-green-700 transition-colors">
                    Rp {{ number_format($room->harga ?? 0, 0, ',', '.') }}
                </div>
            </div>
            <span class="bg-green-50 text-green-700 text-[10px] font-bold px-2 py-1 rounded border border-green-100">Tersedia</span>
        </div>
        
        <a href="{{ route('kamar.detail', ['no_kamar' => $room->no_kamar]) }}" onclick="event.stopPropagation()" class="detail-btn mt-4 block w-full text-center py-2 rounded-lg bg-gray-50 text-gray-600 font-bold text-xs hover:bg-green-600 hover:text-white transition-all border border-gray-100">
            LIHAT DETAIL
        </a>
    </div>
</article> 
--}}