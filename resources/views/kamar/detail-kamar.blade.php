{{-- resources/views/detail-kamar.blade.php --}}
@extends('layout.master')

@section('content')
<div class="container mx-auto px-6 py-8">
  {{-- Breadcrumb --}}
  <nav class="text-sm text-gray-600 mb-6">
    <a href="#" class="hover:underline">Location</a>
    <span class="mx-2">/</span>
    <a href="#" class="hover:underline">Pondok Siti Hajar</a>
    <span class="mx-2">/</span>
    <span class="text-gray-500">{{ $room->number ?? '101' }}</span>
  </nav>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    {{-- LEFT: Gallery + Details (span 2 columns on large) --}}
    <div class="lg:col-span-2">
      {{-- Gallery: main image (left) + two small images (right) --}}
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 items-start">
        {{-- Main image --}}
        <div class="md:col-span-2 rounded-lg overflow-hidden /60 shadow-inner">
          <img src="{{ asset($room->image ?? 'images/kamar.jpg') }}"
               alt="Kamar {{ $room->number ?? '' }}"
               class="w-full h-80 md:h-96 object-cover rounded-lg">
        </div>

        {{-- Two small images stacked --}}
        <div class="flex flex-col gap-4">
          <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm">
            <img src="{{ asset($room->image2 ?? 'images/kamar-2.jpg') }}" alt="Kamar 2" class="w-full h-36 object-cover rounded-lg">
          </div>
          <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm">
            <img src="{{ asset($room->image3 ?? 'images/kamar-3.jpg') }}" alt="Kamar 3" class="w-full h-36 object-cover rounded-lg">
          </div>
        </div>
      </div>

      {{-- Title & divider --}}
      <h1 class="text-2xl font-semibold mb-3">{{ $room->number ?? '101' }}</h1>
      <div class="border-t border-gray-200 mb-6"></div>

      {{-- Spesifikasi --}}
      <section class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Spesifikasi tipe kamar</h3>
        <ul class="list-disc pl-5 text-gray-700 space-y-1">
          <li>{{ $room->size ?? '4 x 5 meter' }}</li>
          <li>{{ $room->electricity ?? 'Termasuk listrik' }}</li>
        </ul>
      </section>

      {{-- Fasilitas Kamar --}}
      <section class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Fasilitas Kamar</h3>
        <ul class="list-disc pl-5 text-gray-700 grid grid-cols-1 sm:grid-cols-2 gap-y-1">
          @php
            $fasilitas = $room->facilities ?? ['Kasur','Bantal','Kipas Angin','Kamar mandi'];
          @endphp
          @foreach($fasilitas as $f)
            <li>{{ $f }}</li>
          @endforeach
        </ul>
      </section>

      {{-- Peraturan --}}
      <section class="mb-6">
        <h3 class="text-lg font-semibold mb-2">Peraturan untuk kost ini</h3>
        <ul class="list-disc pl-5 text-gray-700 space-y-1">
          @php
            $rules = $room->rules ?? [
              'Tamu boleh menginap',
              'Tamu menginap dikenakan biaya',
              'Tipe ini bisa diisi maks. 2 orang / kamar',
              'Tidak untuk pasangan'
            ];
          @endphp
          @foreach($rules as $r)
            <li>{{ $r }}</li>
          @endforeach
        </ul>
      </section>

      {{-- Fasilitas Umum --}}
      <section class="mb-8">
        <h3 class="text-lg font-semibold mb-2">Fasilitas Umum</h3>
        <ul class="list-disc pl-5 text-gray-700">
          @php
            $common = $room->common_facilities ?? ['R. Jemuran', 'Parkiran'];
          @endphp
          @foreach($common as $c)
            <li>{{ $c }}</li>
          @endforeach
        </ul>
      </section>

      {{-- NOTE: if you have booking form below, keep anchor id here --}}
      <div id="booking-form"></div>
    </div>

    {{-- RIGHT: Sidebar harga & aksi (sticky on large screens) --}}
    <aside class="bg-white rounded-xl shadow p-5 lg:sticky lg:top-28">
      <div class="mb-4">
        @if($room->available ?? true)
          <span class="inline-block bg-green-50 text-green-700 px-3 py-1 rounded-full text-sm border border-green-100">Tersedia</span>
        @else
          <span class="inline-block bg-red-50 text-red-700 px-3 py-1 rounded-full text-sm border border-red-100">Terisi</span>
        @endif
      </div>

      <div class="space-y-3 border border-gray-100 p-3 rounded mb-4">
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600">Per hari</div>
          <div class="text-sm font-semibold">Rp. {{ number_format($room->price_per_day ?? 125000, 0, ',', '.') }}</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600">Per Bulan</div>
          <div class="text-sm font-semibold">Rp. {{ number_format($room->price_per_month ?? 300000, 0, ',', '.') }}</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600">Per minggu</div>
          <div class="text-sm font-semibold">Rp. {{ number_format($room->price_per_week ?? 500000, 0, ',', '.') }}</div>
        </div>
        <div class="flex items-center justify-between">
          <div class="text-sm text-gray-600">Per tahun</div>
          <div class="text-sm font-semibold">Rp. {{ number_format($room->price_per_year ?? 5000000, 0, ',', '.') }}</div>
        </div>
      </div>
    </aside>
  </div>
</div>
@endsection
