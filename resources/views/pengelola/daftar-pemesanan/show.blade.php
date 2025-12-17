@extends('layout.pengelola')

@section('title', 'Detail Pemesanan #' . $pemesanan->id_pemesanan)

@section('content')
<div class="container mx-auto py-8 px-4 sm:px-6 lg:px-8">

    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex mb-1" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('pengelola.pemesanan.index') }}" class="text-sm font-medium text-gray-500 hover:text-green-600 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                        </a>
                    </li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                Order #{{ $pemesanan->id_pemesanan }}
                {{-- Status Badge Dinamis --}}
                @php
                    $statusColor = match($pemesanan->status) {
                        'Lunas' => 'bg-green-100 text-green-800 border-green-200',
                        'Belum Dibayar' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'Dibatalkan' => 'bg-red-100 text-red-800 border-red-200',
                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                    };
                    $statusIcon = match($pemesanan->status) {
                        'Lunas' => 'fa-check-circle',
                        'Belum Dibayar' => 'fa-clock',
                        'Dibatalkan' => 'fa-times-circle',
                        default => 'fa-info-circle'
                    };
                @endphp
                <span class="px-3 py-1 text-sm font-semibold rounded-full border {{ $statusColor }} flex items-center gap-2">
                    <i class="fas {{ $statusIcon }}"></i> {{ $pemesanan->status }}
                </span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Dibuat pada: {{ \Carbon\Carbon::parse($pemesanan->created_at)->format('l, d F Y - H:i') }}
            </p>
        </div>

        {{-- ACTION BUTTONS TOP --}}
        <div class="flex gap-3">
            <button onclick="window.print()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition-all">
                <i class="fas fa-print mr-2"></i> Cetak Invoice
            </button>

            @if($pemesanan->status === 'Belum Dibayar')
                <form action="{{ route('pengelola.pemesanan.verifikasi', $pemesanan->id_pemesanan) }}" method="POST" onsubmit="return confirm('Verifikasi pembayaran ini sekarang?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all">
                        <i class="fas fa-check-double mr-2"></i> Verifikasi Pembayaran
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- KOLOM KIRI (DETAIL ITEM & LAYANAN) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- 1. INFORMASI KAMAR (ROOMS) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-bed text-green-600"></i> Detail Kamar
                    </h2>
                    <span class="text-xs font-medium text-gray-500 bg-white px-2 py-1 rounded border">
                        Total Items: {{ $pemesanan->items->count() }}
                    </span>
                </div>

                <div class="p-6">
                    @if($pemesanan->items->isNotEmpty())
                        <div class="space-y-6">
                            @foreach($pemesanan->items as $item)
                                @php
                                    $checkin = \Carbon\Carbon::parse($item->waktu_checkin);
                                    $checkout = \Carbon\Carbon::parse($item->waktu_checkout);
                                    $durasi = $checkin->diffInDays($checkout) ?: 1; // Minimal 1 hari
                                    $subtotal = $item->harga * $durasi * $item->jumlah_pesan;
                                @endphp
                                <div class="flex flex-col sm:flex-row gap-4 pb-6 border-b border-gray-100 last:border-0 last:pb-0">
                                    {{-- Gambar Kamar (Thumbnail) --}}
                                    <div class="w-full sm:w-32 h-24 bg-gray-200 rounded-lg overflow-hidden shrink-0">
                                        @if($item->kamar->gambar)
                                            <img src="{{ asset('storage/' . $item->kamar->gambar) }}" alt="Kamar" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Detail Text --}}
                                    <div class="grow">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    Kamar No. {{ $item->kamar->no_kamar }}
                                                </h3>
                                                <p class="text-sm text-gray-500">{{ $item->kamar->tipe_kamar ?? 'Standard' }} Room</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-green-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                                <p class="text-xs text-gray-400">Rp {{ number_format($item->harga, 0, ',', '.') }} / malam</p>
                                            </div>
                                        </div>

                                        {{-- Timeline Checkin-Checkout --}}
                                        <div class="mt-3 flex items-center gap-4 bg-green-50 p-3 rounded-lg border border-green-100">
                                            <div class="flex-1">
                                                <p class="text-xs text-gray-500 uppercase font-semibold">Check-In</p>
                                                <p class="text-sm font-medium text-gray-800">{{ $checkin->format('d M Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $checkin->format('H:i') }} WIB</p>
                                            </div>
                                            <div class="flex flex-col items-center justify-center px-2">
                                                <span class="text-xs font-bold text-green-600 bg-white px-2 py-0.5 rounded-full shadow-sm border border-green-200">
                                                    {{ $durasi }} Malam
                                                </span>
                                                <div class="w-full h-0.5 bg-green-300 mt-1 relative">
                                                    <i class="fas fa-chevron-right absolute -right-1 -top-1.5 text-green-500 text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 text-right">
                                                <p class="text-xs text-gray-500 uppercase font-semibold">Check-Out</p>
                                                <p class="text-sm font-medium text-gray-800">{{ $checkout->format('d M Y') }}</p>
                                                <p class="text-xs text-gray-500">{{ $checkout->format('H:i') }} WIB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-bed fa-3x mb-3 opacity-30"></i>
                            <p>Tidak ada data kamar.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 2. INFORMASI LAYANAN (SERVICES) --}}
            @if($pemesanan->service && $pemesanan->service->isNotEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-concierge-bell text-blue-600"></i> Layanan Tambahan
                    </h2>
                </div>
                <div class="p-0">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-6 py-3">Nama Layanan</th>
                                <th class="px-6 py-3 text-center">Qty</th>
                                <th class="px-6 py-3 text-right">Harga Satuan</th>
                                <th class="px-6 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($pemesanan->service as $svc)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $svc->service->name ?? 'Service #' . $svc->id_service }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 text-center">
                                    {{ $svc->qty ?? 1 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 text-right">
                                    Rp {{ number_format($svc->price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800 text-right">
                                    Rp {{ number_format(($svc->price * ($svc->qty ?? 1)), 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>

        {{-- KOLOM KANAN (SIDEBAR INFO) --}}
        <div class="space-y-6">

            {{-- 1. INFORMASI PENYEWA (CUSTOMER CARD) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-linear-to-r from-green-600 to-green-500 px-6 py-4">
                    <h2 class="text-white font-semibold flex items-center gap-2">
                        <i class="fas fa-user-circle"></i> Informasi Penyewa
                    </h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-4 mb-6">
                        <img class="h-16 w-16 rounded-full object-cover border-4 border-gray-100 shadow-sm"
                             src="https://ui-avatars.com/api/?name={{ urlencode($pemesanan->penyewa->username ?? 'Guest') }}&background=random"
                             alt="Avatar">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">{{ $pemesanan->penyewa->username ?? 'Tamu Umum' }}</h3>
                            <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                Member
                            </span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3 text-sm">
                            <i class="fas fa-phone mt-1 text-gray-400 w-4"></i>
                            <span class="text-gray-600">{{ $pemesanan->penyewa->no_telp ?? '-' }}</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm">
                            <i class="fas fa-envelope mt-1 text-gray-400 w-4"></i>
                            <span class="text-gray-600">{{ $pemesanan->penyewa->email ?? '-' }}</span>
                        </div>
                        <div class="flex items-start gap-3 text-sm">
                            <i class="fas fa-map-marker-alt mt-1 text-gray-400 w-4"></i>
                            <span class="text-gray-600">{{ $pemesanan->penyewa->alamat ?? 'Alamat tidak tersedia' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. RINGKASAN PEMBAYARAN --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="font-semibold text-gray-800">Ringkasan Tagihan</h2>
                </div>
                <div class="p-6 space-y-3">
                    {{-- Hitung Subtotal (Opsional, jika ingin ditampilkan terpisah) --}}
                    @php
                        $totalKamar = 0;
                        foreach($pemesanan->items as $i) {
                            $dur = \Carbon\Carbon::parse($i->waktu_checkin)->diffInDays(\Carbon\Carbon::parse($i->waktu_checkout)) ?: 1;
                            $totalKamar += ($i->harga * $dur * $i->jumlah_pesan);
                        }

                        $totalService = 0;
                        if($pemesanan->service) {
                            foreach($pemesanan->service as $s) {
                                $totalService += ($s->price * ($s->qty ?? 1));
                            }
                        }
                    @endphp

                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Total Sewa Kamar</span>
                        <span>Rp {{ number_format($totalKamar, 0, ',', '.') }}</span>
                    </div>

                    @if($totalService > 0)
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Total Layanan</span>
                        <span>Rp {{ number_format($totalService, 0, ',', '.') }}</span>
                    </div>
                    @endif

                    <div class="border-t border-dashed border-gray-300 my-2 pt-2"></div>

                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-900 text-lg">Total Bayar</span>
                        <span class="font-bold text-green-600 text-xl">
                            Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="mt-4 text-xs text-gray-400 text-center">
                        *Harga sudah termasuk pajak & biaya layanan
                    </div>
                </div>

                {{-- FOOTER ACTION --}}
                @if($pemesanan->status === 'Lunas')
                    <div class="bg-green-50 px-6 py-3 border-t border-green-100 text-center">
                        <p class="text-sm text-green-800 font-medium">
                            <i class="fas fa-check-circle mr-1"></i> Pembayaran Selesai
                        </p>
                    </div>
                @elseif($pemesanan->status === 'Dibatalkan')
                    <div class="bg-red-50 px-6 py-3 border-t border-red-100 text-center">
                        <p class="text-sm text-red-800 font-medium">
                            <i class="fas fa-times-circle mr-1"></i> Pesanan Dibatalkan
                        </p>
                    </div>
                @endif
            </div>

            {{-- 3. HAPUS PESANAN --}}
            <div class="mt-8">
                 <form action="{{ route('pengelola.pemesanan.destroy', $pemesanan->id_pemesanan) }}" method="POST" onsubmit="return confirm('PERINGATAN: Menghapus data ini bersifat permanen. Lanjutkan?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-red-600 hover:text-red-700 text-sm font-medium hover:underline transition-all">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus Data Pesanan Ini
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- CSS KHUSUS UNTUK PRINT (Agar tampilan rapi saat dicetak) --}}
<style>
    @media print {
        body * { visibility: hidden; }
        .container, .container * { visibility: visible; }
        .container { position: absolute; left: 0; top: 0; width: 100%; padding: 0; margin: 0; }
        button, nav, .fa-arrow-left { display: none !important; } /* Sembunyikan tombol saat print */
        .shadow-sm, .shadow-md { box-shadow: none !important; border: 1px solid #ddd; }
        .bg-gray-50 { background-color: #f9fafb !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
