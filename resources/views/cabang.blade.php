@extends('layout.master')
@section('title', $cabang->nama_cabang)

@section('content')
<header>
    <h1>Pondok Siti Hajar</h1>
</header>

@if(strtolower($cabang->kategori_cabang) === 'kost')
<div class="cabang-detail">
        <h1>{{ $cabang->nama_cabang }}</h1>
        <p><strong>Lokasi:</strong> {{ $cabang->lokasi }}</p>
        <p><strong>Kategori:</strong> {{ ucfirst($cabang->kategori_cabang) }}</p>
        <p><strong>Jumlah Kamar:</strong> {{ $cabang->jumlah_kamar }}</p>
        <p>{{ $cabang->deskripsi }}</p>

        @if($cabang->gambar_cabang)
            <div class="gambar">
                <img src="{{ asset('storage/cabang/' . $cabang->gambar_cabang) }}" alt="{{ $cabang->nama_cabang }}" style="max-width:100%;height:auto;">
            </div>
        @endif
</div>
            
@elseif(strtolower($cabang->kategori_cabang) === 'villa')
<div class="cabang-detail">
        <h1>{{ $cabang->nama_cabang }}</h1>
        <p><strong>Lokasi:</strong> {{ $cabang->lokasi }}</p>
        <p><strong>Kategori:</strong> {{ ucfirst($cabang->kategori_cabang) }}</p>
        <p><strong>Jumlah Kamar:</strong> {{ $cabang->jumlah_kamar }}</p>
        <p>{{ $cabang->deskripsi }}</p>

        @if($cabang->gambar_cabang)
            <div class="gambar">
                <img src="{{ asset('storage/cabang/' . $cabang->gambar_cabang) }}" alt="{{ $cabang->nama_cabang }}" style="max-width:100%;height:auto;">
            </div>
        @endif
</div>
@else
<div>
    <p>Informasi tambahan untuk kategori: {{ $cabang->kategori_cabang }}</p>
</div>
@endif

@endsection