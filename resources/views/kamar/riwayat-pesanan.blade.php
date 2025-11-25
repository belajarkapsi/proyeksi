@extends('layout.master')
@section('title', 'Riwayat Pesanan')

@section('content')

<div class="min-h-[70vh] bg-gray-50/50 pb-10">
    {{-- Header & Back Button Sticky --}}
    <div class="sticky top-16 z-30 bg-white/80 backdrop-blur-md border-b border-gray-200 py-4 mb-8 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative flex items-center justify-center">

            <a href="{{ route('dashboard') }}" class="absolute left-4 sm:left-8 text-gray-500 hover:text-green-600 transition-colors flex items-center gap-1 group">
                <div class="p-2 rounded-full group-hover:bg-green-50 transition-colors">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                <span class="hidden sm:inline font-medium text-sm">Kembali</span>
            </a>

            <h1 class="md:text-5xl text-2xl font-bold text-gray-800 flex items-center gap-2">
                <svg class="md:size-9 size-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Riwayat Pesanan
            </h1>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- KONDISI KOSONG --}}
        @if($orders->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="bg-white p-6 rounded-full shadow-sm mb-6 border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Riwayat</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">Anda belum pernah melakukan pemesanan kamar. Yuk cari kamar nyaman untukmu sekarang!</p>
                <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-green-600 text-white rounded-xl font-bold shadow-lg hover:bg-green-700 hover:-translate-y-1 transition-all transform">
                    Cari Kamar Sekarang
                </a>
            </div>
        @else

            {{-- GRID PESANAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($orders as $order)
                    {{-- Logic Warna & Status --}}
                    @php
                        $statusColor = match($order->status) {
                            'Belum Dibayar' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'Lunas' => 'bg-green-50 text-green-700 border-green-200',
                            'Dibatalkan' => 'bg-red-50 text-red-700 border-red-200',
                            default => 'bg-gray-50 text-gray-700 border-gray-200'
                        };

                        // Ambil item pertama untuk thumbnail (jika perlu)
                        $firstItem = $order->items->first();
                    @endphp

                    <div class="bg-white rounded-2xl p-0 shadow-sm hover:shadow-xl hover:border-3 border-green-400 transition-all duration-100 group overflow-hidden flex flex-col">

                        {{-- HEADER CARD --}}
                        <div class="px-6 py-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Kode Booking</span>
                                <span class="font-mono font-bold text-gray-800 text-lg">#{{ $order->id_pemesanan }}</span>
                            </div>
                            <div class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }} uppercase tracking-wide">
                                {{ $order->status }}
                            </div>
                        </div>

                        {{-- BODY CARD --}}
                        <div class="p-6 flex-1">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $order->cabang->nama_cabang ?? 'Cabang Tidak Dikenal' }}</h3>
                                    <p class="text-sm text-gray-500 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        {{ $order->waktu_pemesanan->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 mb-1">Total Tagihan</p>
                                    <p class="text-lg font-bold text-green-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>

                            {{-- Detail Items (Ringkasan) --}}
                            <div class="bg-gray-50 rounded-xl p-3 text-sm text-gray-600 space-y-2">
                                <div class="flex justify-between">
                                    <span>Jumlah Kamar:</span>
                                    <span class="font-medium text-gray-900">{{ $order->items->count() }} Unit</span>
                                </div>
                                @if($firstItem)
                                <div class="flex justify-between">
                                    <span>Tipe Kamar:</span>
                                    <span class="font-medium text-gray-900">{{ $firstItem->kamar->tipe_kamar ?? '-' }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- FOOTER / ACTION --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                            <a href="{{ route('booking.pembayaran', $order->id_pemesanan) }}"
                                class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 hover:text-green-600 transition-colors group-hover:translate-x-1 transform duration-200">
                                Lihat Detail Pembayaran
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection
