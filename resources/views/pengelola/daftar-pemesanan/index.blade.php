@extends('layout.pengelola')

@section('title', 'Daftar Pemesanan')

@section('content')
{{-- Navbar --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-4">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Data Pemesanan</span>
            </li>
        </ol>
    </nav>
</div>

<div class="container mx-auto py-4 px-4 sm:px-6 lg:px-8">
    {{-- Header Section --}}
    <div class="sm:flex sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pemesanan</h1>
            <p class="mt-1 text-sm text-gray-500">
                Kelola data penyewaan dan pesanan layanan untuk cabang Anda.
            </p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('pengelola.pemesanan.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Pemesanan
            </a>
        </div>
    </div>

    {{-- Search & Filter Section --}}
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
        <form method="GET" action="{{ route('pengelola.pemesanan.index') }}" class="flex flex-col md:flex-row gap-4">

            {{-- Search Input --}}
            <div class="grow">
                <label for="q" class="sr-only">Cari</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input type="text" name="q" id="q" value="{{ request('q') }}" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-green-500 focus:border-green-500 sm:text-sm" placeholder="Cari ID Pemesanan atau Nama Penyewa...">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="w-full md:w-48">
                <select name="status" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                    <option value="">— Semua Status —</option>
                    <option value="Belum Dibayar" {{ request('status') == 'Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Filter
                </button>
                <a href="{{ route('pengelola.pemesanan.index') }}" class="inline-flex justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($pemesanan as $p)
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200 flex flex-col h-full hover:shadow-md transition-shadow duration-300">

                {{-- Card Header --}}
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200 flex justify-between items-start">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ $p->penyewa->username ?? 'Guest' }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">
                            ID: #{{ $p->id_pemesanan }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-building mr-1"></i> {{ $p->cabang->nama_cabang ?? '-' }}
                        </p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        {{ $p->status == 'Lunas' ? 'bg-green-100 text-green-800' :
                        ($p->status == 'Belum Dibayar' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $p->status }}
                    </span>
                </div>

                {{-- Card Body --}}
                <div class="px-4 py-4 grow">

                    {{-- List Kamar --}}
                    <div class="mb-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Item Sewa</h4>
                        @if($p->items && $p->items->isNotEmpty())
                            <ul class="space-y-3">
                                @foreach($p->items as $item)
                                    <li class="text-sm text-gray-700 bg-gray-50 p-2 rounded border border-gray-100">
                                        <div class="flex justify-between font-semibold">
                                            <span>Kamar {{ $item->kamar->no_kamar ?? '?' }}</span>
                                            <span>x{{ $item->jumlah_pesan }}</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <div>Check-in: {{ \Carbon\Carbon::parse($item->waktu_checkin)->format('d M Y') }}</div>
                                            <div>Check-out: {{ \Carbon\Carbon::parse($item->waktu_checkout)->format('d M Y') }}</div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada item kamar.</p>
                        @endif
                    </div>

                    {{-- List Layanan --}}
                    @if($p->service && $p->service->isNotEmpty())
                        <div class="mb-4">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Layanan Tambahan</h4>
                            <ul class="space-y-1">
                                @foreach($p->service as $svc)
                                    <li class="flex justify-between text-sm text-gray-600 border-b border-gray-100 pb-1 last:border-0">
                                        <span>{{ $svc->service->name ?? 'Layanan ?' }}</span>
                                        <span class="font-medium">Rp {{ number_format($svc->price ?? 0, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Card Footer (Total & Actions) --}}
                <div class="px-4 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm font-medium text-gray-500">Total Tagihan</span>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($p->total_harga ?? 0, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex space-x-2">
                        {{-- Tombol View --}}
                        <a href="{{ route('pengelola.pemesanan.show', $p->id_pemesanan) }}" class="flex-1 text-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Detail
                        </a>

                        {{-- Tombol Verifikasi (Hanya jika belum bayar) --}}
                        @if($p->status === 'Belum Dibayar')
                            <form action="{{ route('pengelola.pemesanan.verifikasi', $p->id_pemesanan) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi pembayaran ini menjadi LUNAS?');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Verifikasi
                                </button>
                            </form>
                        @endif

                        {{-- Tombol Hapus --}}
                        <form action="{{ route('pengelola.pemesanan.destroy', $p->id_pemesanan) }}" method="POST" class="flex-none" onsubmit="return confirm('Hapus data pemesanan ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex justify-center items-center px-3 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-12 text-center bg-white rounded-lg border border-gray-200 border-dashed">
                <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pemesanan</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat pemesanan baru atau tunggu penyewa melakukan booking.</p>
                <div class="mt-6">
                    <a href="{{ route('pengelola.pemesanan.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Buat Pemesanan Baru
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $pemesanan->withQueryString()->links() }}
    </div>
</div>
@endsection
