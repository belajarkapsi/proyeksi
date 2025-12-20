@extends('layout.pengelola')
@section('title', 'Detail Penyewa - ' . $user->nama_lengkap)

@section('content')
<div class="container mx-auto px-0 sm:px-2 md:px-4 lg:px-6 xl:px-8">
    {{-- Breadcrumb --}}
    <div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-0 -mt-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <span class="text-gray-400">/</span>
                    <span class="text-green-600 text-sm font-medium">Data Penyewa</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mt-4 mb-6">
        <a href="{{ route('pengelola.penyewa.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-green-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Data Penyewa
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KOLOM KIRI: Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-green-600 h-24"></div>
                <div class="px-6 pb-6 relative">
                    {{-- Foto Profil --}}
                    <div class="-mt-12 mb-4">
                        @if($user->foto_profil)
                            <img src="{{ asset('storage/' . $user->foto_profil) }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-md bg-white">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white shadow-md bg-green-100 flex items-center justify-center text-green-600 text-3xl font-bold">
                                {{ substr($user->nama_lengkap, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <h1 class="text-xl font-bold text-gray-900">{{ $user->nama_lengkap }}</h1>
                    <p class="text-sm text-gray-500">@ {{ $user->username }}</p>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-phone w-6 text-center text-gray-400"></i>
                            <span class="ml-2">{{ $user->no_telp }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-envelope w-6 text-center text-gray-400"></i>
                            <span class="ml-2">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-start text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt w-6 text-center text-gray-400 mt-1"></i>
                            <span class="ml-2">{{ $user->alamat ?? '-' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-birthday-cake w-6 text-center text-gray-400"></i>
                            <span class="ml-2">
                                {{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d M Y') : '-' }}
                                ({{ $user->usia ?? '-' }} Thn)
                            </span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-venus-mars w-6 text-center text-gray-400"></i>
                            <span class="ml-2">{{ $user->jenis_kelamin }}</span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="{{ route('pengelola.penyewa.edit', $user->id_penyewa) }}" class="block w-full text-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition font-semibold text-sm">
                            Edit Profil Penyewa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: Histori Transaksi --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h2 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-history text-green-600"></i> Riwayat Pemesanan
                    </h2>
                    <span class="text-xs font-medium bg-white px-2 py-1 rounded border border-gray-200 text-gray-500">
                        Khusus Cabang Ini
                    </span>
                </div>

                <div class="overflow-x-auto grow">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500">
                                <th class="px-6 py-3 font-semibold">ID Pesanan</th>
                                <th class="px-6 py-3 font-semibold">Tanggal</th>
                                <th class="px-6 py-3 font-semibold">Total</th>
                                <th class="px-6 py-3 font-semibold text-center">Status</th>
                                <th class="px-6 py-3 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($riwayatPemesanan as $pesanan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                    #{{ $pesanan->id_pemesanan }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($pesanan->waktu_pemesanan)->format('d M Y') }}
                                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($pesanan->waktu_pemesanan)->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-800">
                                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusClass = match($pesanan->status) {
                                            'Lunas' => 'bg-green-100 text-green-700 border-green-200',
                                            'Belum Dibayar' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                            'Dibatalkan' => 'bg-red-100 text-red-700 border-red-200',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                        {{ $pesanan->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('pengelola.pemesanan.show', $pesanan->id_pemesanan) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium hover:underline">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-shopping-cart fa-2x mb-2 opacity-30"></i>
                                    <p class="text-sm">Belum ada riwayat pemesanan di cabang ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
