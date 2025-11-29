@extends('layout.master')
@section('title', 'Detail Villa - ' . $cabang->nama_cabang)

@section('content')
<div class="container mx-auto py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">
        Detail Villa: {{ $cabang->nama_cabang }}
    </h1>

    <p class="text-gray-600 mb-6">
        Lokasi: <strong>{{ $cabang->lokasi }}</strong><br>
        Kategori: <strong>{{ $cabang->kategori_cabang }}</strong>
    </p>

    {{-- tambahkan informasi lain tentang villa di sini --}}
</div>
@endsection