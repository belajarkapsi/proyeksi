@extends('layout.pdf')

@section('title', 'Laporan Keuangan')

@section('content')
<style>
    /* Styling Standar untuk PDF */
    body {
        font-family: sans-serif;
        color: #333;
        font-size: 12px;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }
    .header h1 {
        margin: 0;
        font-size: 20px;
        text-transform: uppercase;
        color: #111;
    }
    .header p {
        margin: 4px 0;
        font-size: 12px;
        color: #555;
    }
    
    /* Tabel Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 10px;
        vertical-align: middle;
    }
    th {
        background-color: #f4f4f4;
        color: #333;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 11px;
    }
    /* Zebra striping untuk baris */
    tr:nth-child(even) {
        background-color: #fbfbfb;
    }
    
    /* Helper Classes */
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-left { text-align: left; }
    .font-bold { font-weight: bold; }
    
    /* Badge ID styling (opsional agar terlihat seperti tag) */
    .badge {
        background-color: #eee;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-family: monospace;
    }

    /* Footer Total */
    tfoot tr {
        background-color: #eef2ff; /* Sedikit biru muda */
        border-top: 2px solid #aaa;
    }
    .total-label {
        text-align: right;
        font-weight: bold;
        padding-right: 15px;
    }
    .total-amount {
        text-align: right;
        font-weight: bold;
        color: #1a1a1a;
        font-size: 13px;
    }
</style>

{{-- HEADER --}}
<div class="header">
    <h1>Laporan Keuangan</h1>
    <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    {{-- Jika ada variabel filter bulan/tahun dikirim dari controller, bisa ditampilkan disini --}}
    @if(request('bulan') && request('tahun'))
        <p>Periode: {{ date('F', mktime(0,0,0, request('bulan'), 1)) }} {{ request('tahun') }}</p>
    @endif
</div>

{{-- TABEL --}}
<table>
    <thead>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th width="25%" class="text-center">Tanggal</th>
            <th width="35%" class="text-center">ID Pemesanan</th>
            <th width="35%" class="text-right">Total Transaksi</th>
        </tr>
    </thead>
    <tbody>
        {{-- Inisialisasi variabel total --}}
        @php $grandTotal = 0; @endphp

        @foreach($data as $key => $row)
        @php 
            // Akumulasi total
            $grandTotal += $row->total_harga; 
        @endphp
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td class="text-center">{{ $row->waktu_pemesanan->format('d/m/Y') }}</td>
            <td class="text-center">
                <span class="badge">#{{ $row->id_pemesanan }}</span>
            </td>
            <td class="text-right">Rp {{ number_format($row->total_harga) }}</td>
        </tr>
        @endforeach
    </tbody>
    
    {{-- FOOTER (GRAND TOTAL) --}}
    <tfoot>
        <tr>
            <td colspan="3" class="total-label">TOTAL PENDAPATAN</td>
            <td class="total-amount">Rp {{ number_format($grandTotal) }}</td>
        </tr>
    </tfoot>
</table>

{{-- Disclaimer kecil --}}
<div style="margin-top: 30px; font-size: 10px; color: #888; text-align: center; border-top: 1px dashed #ccc; padding-top: 10px;">
    <em>Laporan ini sah dan digenerate otomatis oleh sistem komputer.</em>
</div>

@endsection