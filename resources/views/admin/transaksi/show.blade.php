@extends('layout.admin')
@section('title', 'Detail Transaksi #' . $transaksi->id_pemesanan)

@section('content')
<div class="container-fluid py-4 px-3 px-md-5">
    {{-- Header & Breadcrumb Section --}}
    <div class="flex flex-col md:flex-row justify-between align-items-start align-items-md-center mb-6 gap-3">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-xs">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-chevron-right text-[8px] mx-1"></i>
                        <a href="{{ route('transaksi.index') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">Riwayat Transaksi</a>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-chevron-right text-[8px] mx-1"></i>
                        <span class="font-medium text-emerald-600">Detail #{{ $transaksi->id_pemesanan }}</span>
                    </li>
                </ol>
            </nav>
            <div class="flex items-center gap-3">
                <a href="{{ route('transaksi.index') }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <h3 class="text-2xl font-extrabold text-gray-800 tracking-tight mb-0">
                    Detail Transaksi
                </h3>
            </div>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button onclick="window.print()" class="flex-1 md:flex-none px-4 py-2 bg-emerald-600 text-white rounded-xl shadow-md hover:bg-emerald-700 transition font-bold text-sm">
                <i class="fas fa-print mr-2"></i> Cetak Invoice
            </button>
        </div>
    </div>

    <div class="row">
        {{-- Kolom Kiri: Ringkasan Pelanggan & Status --}}
        <div class="col-lg-4">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                    <h5 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-0">Informasi Pelanggan</h5>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-black text-xl border border-emerald-200 shadow-sm">
                            {{ strtoupper(substr($transaksi->penyewa->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Nama Penyewa</div>
                            <div class="text-lg font-black text-gray-800 leading-tight">{{ $transaksi->penyewa->name }}</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <small class="text-[10px] font-bold text-gray-400 uppercase d-block mb-1">Lokasi Cabang</small>
                            <div class="text-sm font-bold text-gray-700 flex items-center">
                                <i class="fas fa-building text-emerald-500 mr-2"></i> {{ $transaksi->cabang->nama_cabang }}
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <small class="text-[10px] font-bold text-gray-400 uppercase d-block mb-1">Waktu Transaksi</small>
                            <div class="text-sm font-bold text-gray-700 flex items-center">
                                <i class="far fa-calendar-check text-emerald-500 mr-2"></i> 
                                {{ \Carbon\Carbon::parse($transaksi->waktu_pemesanan)->translatedFormat('d M Y, H:i') }} WIB
                            </div>
                        </div>

                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <small class="text-[10px] font-bold text-gray-400 uppercase d-block mb-1">Status Pembayaran</small>
                            @php
                                $statusStyle = [
                                    'Lunas' => 'bg-emerald-500 text-white',
                                    'Belum Dibayar' => 'bg-amber-500 text-white',
                                    'Dibatalkan' => 'bg-rose-500 text-white'
                                ][$transaksi->status] ?? 'bg-gray-500 text-white';
                            @endphp
                            <span class="inline-block px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm {{ $statusStyle }}">
                                {{ $transaksi->status }}
                            </span>
                        </div>

                        @if($transaksi->status == 'Dibatalkan')
                        <div class="p-3 rounded-xl bg-rose-50 border border-rose-100">
                            <small class="text-[10px] font-bold text-rose-400 uppercase d-block mb-1">Alasan Pembatalan</small>
                            <p class="text-xs font-bold text-rose-700 mb-0">"{{ $transaksi->alasan_batal }}"</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Detail Item & Billing --}}
        <div class="col-lg-8">
            {{-- Rincian Kamar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
                    <h5 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-0"><i class="fas fa-bed mr-2 text-emerald-500"></i> Rincian Kamar</h5>
                </div>
                <div class="table-responsive">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider border-b border-gray-50">
                                <th class="px-6 py-3">No. Kamar</th>
                                <th class="px-6 py-3">Tipe</th>
                                <th class="px-6 py-3">Periode Menginap</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($transaksi->items as $item)
                            <tr class="text-sm font-bold text-gray-700">
                                <td class="px-6 py-4"><span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded text-xs">#{{ $item->kamar->no_kamar }}</span></td>
                                <td class="px-6 py-4 text-gray-500 font-medium">{{ $item->kamar->tipe_kamar }}</td>
                                <td class="px-6 py-4">
                                    <div class="text-[11px] leading-tight">
                                        {{ $item->waktu_checkin->format('d/m/Y') }} - {{ $item->waktu_checkout->format('d/m/Y') }}
                                        <div class="text-emerald-500 font-black mt-1 uppercase text-[9px]">
                                            {{ $item->waktu_checkin->diffInDays($item->waktu_checkout) }} Malam
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-gray-900">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Layanan Tambahan (Jika Ada) --}}
            @if($transaksi->service->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-4">
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50">
                    <h5 class="text-sm font-black text-gray-800 uppercase tracking-widest mb-0"><i class="fas fa-concierge-bell mr-2 text-emerald-500"></i> Layanan Tambahan</h5>
                </div>
                <div class="table-responsive">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[11px] font-extrabold text-gray-400 uppercase tracking-wider border-b border-gray-50">
                                <th class="px-6 py-3">Layanan</th>
                                <th class="px-6 py-3 text-center">Qty</th>
                                <th class="px-6 py-3 text-right">Harga</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($transaksi->service as $svc)
                            <tr class="text-sm font-bold text-gray-700">
                                <td class="px-6 py-4 text-gray-800">{{ $svc->service->name }}</td>
                                <td class="px-6 py-4 text-center"><span class="bg-gray-100 px-2 py-1 rounded text-xs">{{ $svc->quantity }}x</span></td>
                                <td class="px-6 py-4 text-right text-gray-500 font-medium">Rp{{ number_format($svc->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-gray-900">Rp{{ number_format($svc->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Grand Total Card --}}
            <div class="bg-emerald-600 rounded-2xl shadow-lg p-6 text-white flex flex-col md:flex-row justify-between items-center gap-4 border border-emerald-500">
                <div class="text-center text-md-left">
                    <h4 class="text-lg font-black uppercase tracking-widest mb-0">Total Pembayaran</h4>
                    <p class="text-emerald-100 text-xs mb-0 opacity-80 font-medium">Layanan sudah termasuk pajak pemerintah & biaya operasional.</p>
                </div>
                <div class="text-center text-md-right">
                    <h2 class="text-3xl font-black mb-0 tracking-tighter">Rp{{ number_format($transaksi->total_harga, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Global Typography */
    body { background-color: #f8fafc; font-family: 'Inter', sans-serif; }
    .font-black { font-weight: 900; }
    .font-extrabold { font-weight: 800; }
    
    /* Table Fixes */
    .table-responsive { width: 100%; overflow-x: auto; }
    
    /* Print Layout Optimization */
    @media print {
        .btn, nav, .sidebar, .w-9.h-9, footer { display: none !important; }
        .container-fluid { padding: 0 !important; }
        .row { display: block !important; }
        .col-lg-4, .col-lg-8 { width: 100% !important; max-width: 100% !important; flex: none !important; }
        .bg-emerald-600 { background-color: #10b981 !important; -webkit-print-color-adjust: exact; color: white !important; }
        .bg-gray-50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
        .rounded-2xl { border-radius: 8px !important; }
        .shadow-sm, .shadow-lg { box-shadow: none !important; border: 1px solid #e5e7eb !important; }
    }

    /* Scrollbar Style */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #f1f5f9; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
@endsection