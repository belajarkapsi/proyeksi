@extends('layout.pdf')

@section('title', 'Laporan Hunian')

@section('content')
<style>
    /* Styling khusus untuk PDF */
    body {
        font-family: sans-serif;
        color: #333;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
        border-bottom: 2px solid #444;
        padding-bottom: 10px;
    }
    .header h1 {
        margin: 0;
        font-size: 20px;
        text-transform: uppercase;
    }
    .header p {
        margin: 5px 0 0;
        font-size: 12px;
        color: #666;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
        text-align: center;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    
    /* Utility untuk status warna */
    .bar-container {
        background-color: #e0e0e0;
        width: 100%;
        height: 6px;
        border-radius: 3px;
        margin-top: 4px;
    }
    .bar-fill {
        height: 100%;
        background-color: #3B82F6; /* Biru */
        border-radius: 3px;
    }
    
    /* Footer Total */
    tfoot tr {
        background-color: #eef2ff;
        font-weight: bold;
    }
</style>

{{-- HEADER LAPORAN --}}
<div class="header">
    <h1>Laporan Okupansi Hunian</h1>
    <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    @if(isset($bulan) && isset($tahun))
    <p>Periode: {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}</p>
    @endif
</div>

{{-- TABEL DATA --}}
<table>
    <thead>
        <tr>
            <th width="30%">Cabang</th>
            <th width="15%">Total Kamar</th>
            <th width="15%">Dihuni</th>
            <th width="15%">Kosong</th>
            <th width="25%">Hunian (%)</th>
        </tr>
    </thead>
    <tbody>
        {{-- Inisialisasi variabel total untuk footer --}}
        @php 
            $sumTotal = 0;
            $sumDihuni = 0;
            $sumKosong = 0;
        @endphp

        @foreach($data as $row)
        @php
            $kosong = $row->total_kamar - $row->dihuni;
            $persen = ($row->total_kamar > 0) ? round(($row->dihuni / $row->total_kamar) * 100, 2) : 0;
            
            // Tambahkan ke total akumulasi
            $sumTotal += $row->total_kamar;
            $sumDihuni += $row->dihuni;
            $sumKosong += $kosong;
        @endphp
        <tr>
            <td>{{ $row->nama_cabang }}</td>
            <td class="text-center">{{ $row->total_kamar }}</td>
            <td class="text-center" style="color: #2563EB;">{{ $row->dihuni }}</td>
            <td class="text-center" style="color: #10B981;">{{ $kosong }}</td>
            <td>
                <div style="float:left; width: 40px; text-align:right; margin-right:5px;">
                    {{ $persen }}%
                </div>
                {{-- Visual Bar sederhana --}}
                <div class="bar-container" style="overflow:hidden;">
                    <div class="bar-fill" style="width: {{ $persen }}%;"></div>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-right">TOTAL KESELURUHAN</td>
            <td class="text-center">{{ $sumTotal }}</td>
            <td class="text-center">{{ $sumDihuni }}</td>
            <td class="text-center">{{ $sumKosong }}</td>
            <td>
                @php
                    $totalPersen = ($sumTotal > 0) ? round(($sumDihuni / $sumTotal) * 100, 2) : 0;
                @endphp
                Rata-rata: {{ $totalPersen }}%
            </td>
        </tr>
    </tfoot>
</table>

<div style="margin-top: 20px; font-size: 10px; color: #888; text-align: right;">
    <em>Dokumen ini digenerate secara otomatis oleh sistem.</em>
</div>
@endsection