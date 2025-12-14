@extends('layout.admin')

@section('title', 'Daftar Pemesanan')

@section('content')
<div class="container mx-auto py-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-semibold">Daftar Pemesanan</h1>

        <div>
            <a href="{{ route('daftar-pemesanan.create') }}"
               class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">
                Buat Pemesanan
            </a>
        </div>
    </div>

    {{-- Search & filter --}}
    <form method="get" action="{{ url('/admin/daftar-pemesanan') }}" class="mb-6 flex gap-2">
        <input type="text"
               name="q"
               value="{{ old('q', $q ?? '') }}"
               placeholder="Cari id atau username penyewa"
               class="border rounded px-3 py-2 w-1/3" />

        <select name="status" class="border rounded px-3 py-2">
            <option value="">— Semua Status —</option>
            <option value="Belum Dibayar" {{ ($status ?? '')=='Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
            <option value="Lunas" {{ ($status ?? '')=='Lunas' ? 'selected' : '' }}>Lunas</option>
            <option value="Dibatalkan" {{ ($status ?? '')=='Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        </select>

        <button type="submit" class="px-4 py-2 rounded bg-gray-800 text-white">
            Filter
        </button>
        <a href="{{ url('/admin/daftar-pemesanan') }}" class="px-3 py-2 rounded border">
            Reset
        </a>
    </form>

    {{-- Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse($pemesanan as $p)
            <div class="border rounded p-4 shadow-sm bg-white">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm text-gray-500">
                            ID: <strong>{{ $p->id_pemesanan }}</strong>
                        </div>

                        <div class="text-lg font-medium mt-1">
                            {{ $p->penyewa->username ?? '-' }}
                        </div>

                        <div class="text-sm text-gray-600">
                            Telp: {{ $p->penyewa->no_telp ?? '-' }}
                        </div>

                        <div class="text-sm text-gray-600">
                            Cabang: {{ $p->cabang->nama_cabang ?? '-' }}
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="text-sm text-gray-500">Status</div>
                        <div class="mt-1 px-3 py-1 rounded text-sm
                            {{ $p->status == 'Lunas'
                                ? 'bg-green-100 text-green-800'
                                : ($p->status == 'Belum Dibayar'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-red-100 text-red-800') }}">
                            {{ $p->status }}
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- ITEMS / KAMAR --}}
                <div class="mb-3">
                    <div class="text-sm font-semibold">Items (Kamar)</div>

                    @if($p->items && $p->items->isNotEmpty())
                        <ul class="text-sm mt-1">
                            @foreach($p->items as $it)
                                <li class="mb-1">
                                    <strong>
                                        {{ $it->kamar->no_kamar ?? 'Kamar tidak ditemukan' }}
                                    </strong>
                                    — qty: {{ $it->jumlah_pesan }}
                                    — harga: {{ number_format($it->harga,0,',','.') }}

                                    <div class="text-xs text-gray-600">
                                        CI: {{ $it->waktu_checkin ?? '-' }}
                                        —
                                        CO: {{ $it->waktu_checkout ?? '-' }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-sm text-gray-500">
                            Tidak ada item kamar.
                        </div>
                    @endif
                </div>

                {{-- SERVICES --}}
                <div class="mb-3">
                    <div class="text-sm font-semibold">Layanan</div>

                    @if($p->service && $p->service->isNotEmpty())
                        <ul class="text-sm mt-1">
                            @foreach($p->service as $ps)
                                <li class="mb-1">
                                    {{ $ps->service->name ?? 'Service tidak ditemukan' }}
                                    — qty: {{ $ps->qty ?? 1 }}
                                    — harga: {{ number_format($ps->price ?? 0,0,',','.') }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-sm text-gray-500">
                            Tidak ada layanan.
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="text-sm">
                        Total:
                        <strong>
                            Rp {{ number_format($p->total_harga ?? 0,0,',','.') }}
                        </strong>
                    </div>

                    <div class="flex gap-2 items-center">
                        {{-- VERIFIKASI --}}
                        @if($p->status === 'Belum Dibayar')
                            <form action="{{ url('/admin/daftar-pemesanan/'.$p->id_pemesanan.'/verifikasi') }}"
                                  method="post"
                                  onsubmit="return confirm('Verifikasi pemesanan ini sebagai LUNAS?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="px-3 py-1 rounded bg-green-600 text-white hover:bg-green-700">
                                    Verifikasi
                                </button>
                            </form>
                        @else
                            <span class="px-3 py-1 rounded bg-green-100 text-green-800 text-sm font-medium">
                                ✔ Sudah Diverifikasi
                            </span>
                        @endif

                        {{-- HAPUS --}}
                        <form action="{{ url('/admin/daftar-pemesanan/'.$p->id_pemesanan) }}"
                              method="post"
                              onsubmit="return confirm('Hapus pemesanan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 rounded border text-red-600">
                                Hapus
                            </button>
                        </form>

                        {{-- VIEW --}}
                        <a href="{{ url('/admin/daftar-pemesanan/'.$p->id_pemesanan) }}"
                           class="px-3 py-1 rounded border">
                            View
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500">
                Belum ada pemesanan.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $pemesanan->withQueryString()->links() }}
    </div>
</div>
@endsection
