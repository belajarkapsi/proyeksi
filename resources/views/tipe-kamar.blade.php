@extends('layout.master')
@section('title', 'Tipe Kamar')

@section('content')
<div class="container mx-auto px-6 py-8">
  {{-- small breadcrumb / header --}}
  <nav class="text-sm text-gray-600 mb-6">
    <a href="#" class="mr-4 hover:underline">Location</a>
    <span class="mr-4">Pondok Siti Hajar</span>
    <span class="text-gray-400">Ekonomis</span>
  </nav>

  {{-- grid cards --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
    @foreach($rooms as $room)
      <article class="relative bg-white rounded-xl shadow-lg p-4 overflow-hidden">
        {{-- image --}}
        <div class="rounded-lg overflow-hidden">
          <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}"
               alt="Kamar {{ $room->no_kamar ?? '—' }}"
               class="w-full h-44 object-cover rounded-md">
        </div>

        {{-- badge nomor & lantai (overlap) --}}
        <div class="absolute left-6 top-36 transform -translate-y-1/2">
          <div class="flex items-center space-x-2">
            <div class="bg-green-600 text-white font-extrabold px-3 py-1 rounded-md text-lg">
              {{ $room->no_kamar ?? '101' }}
            </div>
            <div class="bg-white/90 text-green-700 text-xs px-2 py-0.5 rounded-full border border-green-100">
              Lantai {{ $room->floor ?? '1' }}
            </div>
          </div>
        </div>

        {{-- content --}}
        <div class="mt-4 text-sm text-gray-700">
          <div class="flex items-start justify-between">
            <div>
              <p class="text-xs text-gray-500">Ukuran Kamar :</p>
              <p class="text-sm">4m²</p>
            </div>

            {{-- status tag --}}
            <div class="text-right">
              @if( ($room->available ?? true) )
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

         {{-- tombol --}}
        <div class="mt-4 text-right">   <!-- ✨ Tambahkan text-right disini -->
             @if( ($room->available ?? true) )
                <a href="/kamar/detail-kamar/{{$room->no_kamar}}"
                class="inline-block bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-md shadow">
                 Detail
                </a>
            @else
               <button disabled
                 class="inline-block bg-gray-100 text-gray-400 text-sm font-semibold px-4 py-2 rounded-md border border-gray-200 cursor-not-allowed">
                 Detail
            </button>
             @endif
            </div>
        </div>
      </article>
    @endforeach

    {{-- fallback jika tidak ada kamar --}}
    @if($rooms->isEmpty())
      @for($i=0;$i<6;$i++)
        <div class="bg-white rounded-xl shadow-lg p-4 animate-pulse">
          <div class="rounded-lg overflow-hidden bg-gray-200 h-44 mb-4"></div>
          <div class="h-4 bg-gray-200 rounded w-1/3 mb-2"></div>
          <div class="h-3 bg-gray-200 rounded w-1/2 mb-1"></div>
          <div class="h-3 bg-gray-200 rounded w-2/3 mb-1"></div>
          <div class="h-8 bg-gray-200 rounded w-1/3 mt-4"></div>
        </div>
      @endfor
    @endif
  </div>
</div>

@endsection