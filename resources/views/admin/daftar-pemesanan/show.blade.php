@extends('layout.admin')

@section('title', 'Detail Pemesanan')

@section('content')
<div class="container mx-auto py-6 max-w-3xl">
    <a href="{{ url('/admin/daftar-pemesanan') }}"
       class="inline-block mb-4 text-sm text-blue-600">
        ← Kembali
    </a>

    <div class="border rounded p-4 bg-white">
        <div class="flex justify-between">
            <div>
                <div class="text-sm text-gray-500">
                    ID: <strong>{{ $pemesanan->id_pemesanan }}</strong>
                </div>

                <div class="text-xl font-semibold mt-1">
                    {{ $pemesanan->penyewa->username ?? '-' }}
                </div>

                <div class="text-sm text-gray-600">
                    Telp: {{ $pemesanan->penyewa->no_telp ?? '-' }}
                </div>

                <div class="text-sm text-gray-600">
                    Cabang: {{ $pemesanan->cabang->nama_cabang ?? ($pemesanan->id_cabang ?? '-') }}
                </div>
            </div>

            <div class="text-right">
                <div class="text-sm">Waktu</div>
                <div class="mt-1">
                    {{ optional($pemesanan->waktu_pemesanan)->format('d M Y H:i') ?? '-' }}
                </div>

                <div class="mt-3 px-3 py-1 rounded text-sm
                    {{ $pemesanan->status == 'Lunas'
                        ? 'bg-green-100 text-green-800'
                        : ($pemesanan->status == 'Belum Dibayar'
                            ? 'bg-yellow-100 text-yellow-800'
                            : 'bg-red-100 text-red-800') }}">
                    {{ $pemesanan->status }}
                </div>
            </div>
        </div>

        <hr class="my-4">

        {{-- ================= ITEMS / KAMAR ================= --}}
        <div>
            <h3 class="font-semibold mb-2">Items (Kamar)</h3>

            @if($pemesanan->items && $pemesanan->items->isNotEmpty())
                <ul class="space-y-2">
                    @foreach($pemesanan->items as $it)
                        <li class="border rounded p-2">
                            <div class="flex justify-between">
                                <div>
                                    <div class="font-medium">
                                        {{ $it->kamar->no_kamar ?? ('Kamar '.$it->id_kamar) }}
                                    </div>

                                    <div class="text-sm text-gray-600">
                                        CI: {{ $it->waktu_checkin ?? '-' }}
                                        —
                                        CO: {{ $it->waktu_checkout ?? '-' }}
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div>
                                        Qty: {{ $it->jumlah_pesan ?? 1 }}
                                    </div>
                                    <div class="font-medium">
                                        Rp {{ number_format($it->harga ?? 0,0,',','.') }}
                                    </div>
                                </div>
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

        {{-- ================= SERVICES ================= --}}
        <div class="mt-4">
            <h3 class="font-semibold mb-2">Layanan</h3>

            @if($pemesanan->service && $pemesanan->service->isNotEmpty())
                <ul class="space-y-1 text-sm">
                    @foreach($pemesanan->service as $ps)
                        <li>
                            {{ $ps->service->name ?? 'Service tidak ditemukan' }}
                            — qty: {{ $ps->qty ?? 1 }}
                            — Rp {{ number_format($ps->price ?? 0,0,',','.') }}
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-sm text-gray-500">
                    Tidak ada layanan.
                </div>
            @endif
        </div>

        {{-- ================= TOTAL ================= --}}
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm">Total</div>
            <div class="text-lg font-semibold">
                Rp {{ number_format($pemesanan->total_harga ?? 0,0,',','.') }}
            </div>
        </div>

        {{-- ================= ACTION ================= --}}
        <div class="mt-4 flex gap-2">
            <form action="{{ url('/admin/daftar-pemesanan/'.$pemesanan->id_pemesanan) }}"
                  method="post"
                  onsubmit="return confirm('Hapus pemesanan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-3 py-1 rounded border text-red-600">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
