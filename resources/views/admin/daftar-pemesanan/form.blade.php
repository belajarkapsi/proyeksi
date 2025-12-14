@extends('layout.admin')

@section('title', isset($pemesanan->id_pemesanan) ? 'Edit Pemesanan' : 'Buat Pemesanan')

@section('content')
<div class="container mx-auto py-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            {{ isset($pemesanan->id_pemesanan) ? 'Edit Pemesanan' : 'Buat Pemesanan Baru' }}
        </h1>
    </div>

    <form action="{{ $action }}" method="post" class="bg-white p-6 shadow rounded-lg">
        @csrf
        @if(isset($method) && $method === 'put')
            @method('PUT')
        @endif

        {{-- ================= PENYEWA ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Penyewa</label>
                <select name="id_penyewa" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Pilih Penyewa --</option>
                    @foreach($penyewas as $penyewa)
                        <option value="{{ $penyewa->id_penyewa }}"
                            {{ old('id_penyewa', $pemesanan->id_penyewa) == $penyewa->id_penyewa ? 'selected' : '' }}>
                            {{ $penyewa->username }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                <input type="text"
                       name="no_telp"
                       value="{{ old('no_telp', $pemesanan->penyewa->no_telp ?? '') }}"
                       class="w-full border border-gray-300 rounded px-3 py-2"
                       placeholder="Contoh: 0812xxxx">
            </div>
        </div>

        {{-- ================= CABANG / STATUS / WAKTU ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Cabang</label>
                <select name="id_cabang" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id_cabang }}"
                            {{ old('id_cabang', $pemesanan->id_cabang) == $c->id_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Status Pembayaran</label>
                @php $st = old('status', $pemesanan->status); @endphp
                <select name="status" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="Belum Dibayar" {{ $st=='Belum Dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="Lunas" {{ $st=='Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Dibatalkan" {{ $st=='Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Waktu Pemesanan</label>
                <input type="datetime-local"
                       name="waktu_pemesanan"
                       value="{{ old('waktu_pemesanan', optional($pemesanan->waktu_pemesanan)->format('Y-m-d\TH:i')) }}"
                       class="w-full border border-gray-300 rounded px-3 py-2">
            </div>
        </div>

        <hr class="my-6">

        {{-- ================= KAMAR ================= --}}
        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-lg font-semibold text-gray-800">Daftar Kamar</h2>
                <button type="button" id="add-item"
                        class="bg-blue-50 text-blue-600 px-4 py-2 rounded border hover:bg-blue-100">
                    + Tambah Kamar
                </button>
            </div>

            <div id="items-wrapper" class="space-y-4">
                @php
                    $itemsOld = old('items', $pemesanan->items->map(function($it){
                        return [
                            'no_kamar' => $it->kamar->no_kamar ?? '',
                            'waktu_checkin' => $it->waktu_checkin,
                            'waktu_checkout' => $it->waktu_checkout,
                        ];
                    })->toArray());
                @endphp

                @if(empty($itemsOld))
                    @php $itemsOld = [['no_kamar'=>'','waktu_checkin'=>'','waktu_checkout'=>'']]; @endphp
                @endif

                @foreach($itemsOld as $i => $item)
                <div class="item-row border bg-gray-50 p-4 rounded-lg">
                    <input type="hidden" name="items[{{ $i }}][jumlah_pesan]" value="1">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-3">
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Pilih Kamar</label>
                            <select name="items[{{ $i }}][no_kamar]" class="w-full border rounded px-3 py-2">
                                <option value="">-- Pilih Kamar --</option>
                                @foreach($kamars as $k)
                                    <option value="{{ $k->no_kamar }}"
                                        {{ ($item['no_kamar'] ?? '') == $k->no_kamar ? 'selected' : '' }}>
                                        Kamar {{ $k->no_kamar }} — {{ $k->status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Tanggal Masuk</label>
                            <input type="date" name="items[{{ $i }}][waktu_checkin]"
                                   value="{{ $item['waktu_checkin'] ?? '' }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-500 mb-1 block">Tanggal Keluar</label>
                            <input type="date" name="items[{{ $i }}][waktu_checkout]"
                                   value="{{ $item['waktu_checkout'] ?? '' }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="flex items-end">
                            <button type="button"
                                    class="remove-item w-full bg-red-100 text-red-600 border px-3 py-2 rounded">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ================= SERVICES ================= --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Layanan Tambahan</h2>

            @php
                $selectedServices = old(
                    'services',
                    $pemesanan->service
                        ? $pemesanan->service->pluck('id')->toArray()
                        : []
                );
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @foreach($services as $s)
                    <label class="flex items-start gap-3 border p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="checkbox"
                               name="services[]"
                               value="{{ $s->id }}"
                               class="mt-1"
                               {{ in_array($s->id, $selectedServices) ? 'checked' : '' }}>

                        <div>
                            <div class="font-medium text-gray-800">{{ $s->name }}</div>
                            <div class="text-sm text-gray-500">
                                Rp {{ number_format($s->price ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- ================= TOTAL ================= --}}
        <div class="border-t pt-4 mt-6">
            <label class="block text-sm font-bold text-gray-700 mb-1">Total Harga (Rp)</label>
            <input type="number"
                   name="total_harga"
                   value="{{ old('total_harga', $pemesanan->total_harga) }}"
                   class="w-full border rounded px-3 py-2 text-lg font-bold"
                   required>

            <button type="submit"
                    class="mt-4 w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg">
                Simpan Pemesanan
            </button>
        </div>
    </form>
</div>

{{-- ================= JS ================= --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wrapper = document.getElementById('items-wrapper');
    const addBtn = document.getElementById('add-item');

    addBtn.addEventListener('click', function () {
        const i = wrapper.children.length;

        wrapper.insertAdjacentHTML('beforeend', `
            <div class="item-row border bg-gray-50 p-4 rounded-lg mt-4">
                <input type="hidden" name="items[${i}][jumlah_pesan]" value="1">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-3">
                        <select name="items[${i}][no_kamar]" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih Kamar --</option>
                            @foreach($kamars as $k)
                                <option value="{{ $k->no_kamar }}">
                                    Kamar {{ $k->no_kamar }} — {{ $k->status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="date" name="items[${i}][waktu_checkin]" class="border rounded px-3 py-2">
                    <input type="date" name="items[${i}][waktu_checkout]" class="border rounded px-3 py-2">

                    <button type="button"
                            class="remove-item bg-red-100 text-red-600 border px-3 py-2 rounded">
                        Hapus
                    </button>
                </div>
            </div>
        `);
    });

    wrapper.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endsection
