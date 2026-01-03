@extends('layout.admin')

@section('content')
<div class="container-fluid py-4 px-3 px-md-5">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between align-items-start align-items-md-center mb-6 gap-3">
        <div>
            {{-- Breadcrumb: Ukuran font ditingkatkan ke text-sm --}}
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-emerald-600 transition-colors">
                            <i class="fas fa-home mr-1 text-xs"></i> Dashboard
                        </a>
                    </li>
                    <li class="flex items-center text-gray-400">
                        <i class="fas fa-chevron-right text-[10px] mx-1"></i>
                        <span class="font-medium text-emerald-600">Riwayat Transaksi</span>
                    </li>
                </ol>
            </nav>
            {{-- Judul: Ditingkatkan ke text-3xl --}}
            <h3 class="text-3xl font-extrabold text-gray-800 tracking-tight mb-0">
                Riwayat Transaksi
            </h3>
            {{-- Sub-judul: Ditingkatkan ke text-base --}}
            <p class="text-base text-gray-500 mt-1 font-medium">Pantau dan kelola seluruh aktivitas pembayaran dari semua cabang.</p>
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button onclick="location.reload()" class="flex-1 md:flex-none px-5 py-2.5 bg-white border border-gray-200 text-gray-700 rounded-xl shadow-sm hover:bg-gray-50 transition font-bold text-sm">
                <i class="fas fa-sync-alt mr-2 text-emerald-500"></i> Refresh Data
            </button>
        </div>
    </div>

    {{-- Main Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        {{-- Header Tabel: Ditingkatkan ke text-xs --}}
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-center w-[10%]">ID Pesanan</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest w-[25%]">Penyewa</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest w-[15%]">Cabang</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest w-[15%]">Waktu</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-right w-[15%]">Total Bayar</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-center w-[15%]">Status</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-widest text-center w-[5%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transaksi as $pemesanan)
                    <tr class="hover:bg-emerald-50/30 transition-all duration-200">
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            {{-- ID Badge: Ditingkatkan ke text-sm --}}
                            <span class="text-sm font-bold font-mono text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100">
                                #{{ $pemesanan->id_pemesanan }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 text-emerald-600 flex items-center justify-center font-black text-lg shadow-sm border border-gray-200">
                                    {{ strtoupper(substr($pemesanan->penyewa->nama_lengkap ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    {{-- Nama: Ditingkatkan ke text-base --}}
                                    <div class="text-base font-bold text-gray-800 leading-tight">{{ $pemesanan->penyewa->nama_lengkap ?? 'User Terhapus' }}</div>
                                    {{-- Info: Ditingkatkan ke text-xs --}}
                                    <div class="text-xs text-gray-400 uppercase font-bold mt-0.5 tracking-tight">Customer</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            {{-- Cabang: Ditingkatkan ke text-sm --}}
                            <div class="inline-flex items-center text-sm font-bold text-gray-600 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-100">
                                <i class="fas fa-store mr-2 text-emerald-500"></i>
                                {{ $pemesanan->cabang->nama_cabang }}
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            {{-- Tanggal: Ditingkatkan ke text-base --}}
                            <div class="text-base font-bold text-gray-700">{{ $pemesanan->waktu_pemesanan->format('d M Y') }}</div>
                            {{-- Jam: Ditingkatkan ke text-xs --}}
                            <div class="text-xs text-gray-400 font-bold mt-0.5"><i class="far fa-clock mr-1"></i>{{ $pemesanan->waktu_pemesanan->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-right">
                            {{-- Total: Ditingkatkan ke text-lg --}}
                            <div class="text-lg font-black text-gray-900 tracking-tighter">
                                Rp{{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            @php
                                $statusStyle = [
                                    'Lunas' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                    'Belum Dibayar' => 'bg-amber-100 text-amber-700 border-amber-200',
                                    'Dibatalkan' => 'bg-rose-100 text-rose-700 border-rose-200'
                                ][$pemesanan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            {{-- Status: Ditingkatkan ke text-xs --}}
                            <span class="inline-block px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest border {{ $statusStyle }} shadow-sm min-w-[120px]">
                                {{ $pemesanan->status }}
                            </span>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap text-center">
                            <a href="{{ route('transaksi.show', $pemesanan->id_pemesanan) }}" 
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm border border-emerald-100 mx-auto" 
                               title="Lihat Detail">
                                <i class="fas fa-eye text-sm"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <img src="https://illustrations.popsy.co/emerald/no-data-found.svg" class="w-56 opacity-50 mb-4" alt="Empty">
                                <h4 class="text-xl text-gray-800 font-bold mb-1">Belum Ada Transaksi</h4>
                                <p class="text-gray-500 font-medium text-base">Data riwayat transaksi akan muncul secara otomatis di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Footer Info Data --}}
    <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
        {{-- Info Data: Ditingkatkan ke text-xs --}}
        <div class="text-xs font-black text-gray-400 uppercase tracking-widest">
            Terdeteksi <span class="text-emerald-600">{{ $transaksi->count() }}</span> Data Transaksi Keseluruhan
        </div>
    </div>
</div>

<style>
    /* Styling Scrollbar Modern */
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    /* Font Weight Helpers */
    .font-extrabold { font-weight: 800; }
    .font-black { font-weight: 900; }

    /* Layout Full Width Fix */
    .container-fluid { max-width: 100% !important; }

    /* Pengaturan Baris agar font besar tetap simetris */
    .table thead th { vertical-align: middle; }
    .table tbody td { vertical-align: middle; }
</style>
@endsection