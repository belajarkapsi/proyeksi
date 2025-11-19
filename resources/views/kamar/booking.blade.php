@extends('layout.master')

@section('content')
<div class="container mx-auto px-6 py-8">

    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-600 mb-6">
        <a href="#" class="hover:underline">Location</a>
        <span class="mx-2">/</span>
        <a href="#" class="hover:underline">Pondok Siti Hajar</a>
        <span class="mx-2">/</span>
        <a href="#" class="hover:underline">Ekonomis</a>
        <span class="mx-2">/</span>
        <span class="text-gray-500">{{ $room->number }}</span>
    </nav>

    {{-- Container Form --}}
    <div class="bg-white shadow-xl rounded-2xl p-8 max-w-4xl mx-auto">

        {{-- Title --}}
        <h2 class="text-center text-xl font-semibold mb-8">
            Lantai {{ $room->floor }} | Kamar {{ $room->number }}
        </h2>

        <form action="{{ route('booking.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- LEFT FORM --}}
                <div class="space-y-4">

                    {{-- Nama --}}
                    <div>
                        <label class="text-sm font-semibold">Nama lengkap</label>
                        <input type="text" name="name"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                               placeholder="Enter your Name" required>
                    </div>

                    {{-- Telepon --}}
                    <div>
                        <label class="text-sm font-semibold">Nomor Telepon</label>
                        <input type="text" name="phone"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                               placeholder="Enter your Number" required>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="text-sm font-semibold">Email</label>
                        <input type="email" name="email"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                               placeholder="Enter your email">
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="text-sm font-semibold">Username</label>
                        <input type="text" name="username"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                               placeholder="Enter your username">
                    </div>

                    {{-- Kota --}}
                    <div>
                        <label class="text-sm font-semibold">Kota Asal</label>
                        <input type="text" name="city"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1"
                               placeholder="Enter your Origin">
                    </div>

                </div>

                {{-- RIGHT CARD --}}
                <div class="space-y-4">

                    {{-- Tanggal + Periode --}}
                    <div class="flex items-center space-x-3">
                        <div class="flex-1">
                            <label class="text-sm font-semibold">Pilih tanggal</label>
                            <input type="date" name="date"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 mt-1">
                        </div>

                        <div>
                            <label class="text-sm font-semibold">Periode</label>
                            <select name="periode"
                                    class="border border-gray-300 rounded-md px-3 py-2 mt-1">
                                <option>Per Hari</option>
                                <option>Per Minggu</option>
                                <option>Per Bulan</option>
                                <option>Per Tahun</option>
                            </select>
                        </div>
                    </div>

                    {{-- Kamar Card --}}
                    <div class="flex border rounded-xl shadow p-3 space-x-3">

                        {{-- Image --}}
                        <img src="{{ asset($room->image) }}"
                             class="w-28 h-20 object-cover rounded-lg">

                        <div class="flex-1">
                            <p class="text-sm font-semibold">Kamar {{ $room->number }} | Lantai {{ $room->floor }}</p>
                            <p class="text-xs text-gray-500 mt-1">Fasilitas</p>
                            <ul class="text-xs text-gray-600 leading-4">
                                <li>• AC</li>
                                <li>• Kasur</li>
                            </ul>

                            <p class="text-xs text-gray-500 mt-1">x <span id="quantity">2</span></p>
                        </div>

                        {{-- plus minus --}}
                        <div class="flex flex-col justify-between">
                            <button type="button" id="plus"
                                    class="w-6 h-6 rounded-full bg-green-600 text-white text-center leading-6">+</button>
                            <button type="button" id="minus"
                                    class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 text-center leading-6">-</button>
                        </div>

                        {{-- Harga --}}
                        <div class="text-sm font-semibold whitespace-nowrap">
                            Rp. {{ number_format($room->price_per_day ?? 125000, 0, ',', '.') }}
                        </div>

                    </div>

                    {{-- TOTAL --}}
                    <div class="flex justify-between px-1 mt-4">
                        <p class="text-sm font-semibold">Total</p>
                        <p id="total" class="text-sm font-semibold">
                            Rp. 250.000
                        </p>
                    </div>

                    {{-- BUTTON --}}
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2 rounded-full">
                        Ajukan Sewa
                    </button>

                </div>
            </div>

            <input type="hidden" name="room_id" value="{{ $room->id }}">
        </form>

    </div>

</div>

{{-- JS: Hitung total + plus minus --}}
<script>
    let qty = 2;
    // const price = {{ $room->price_per_day ?? 125000 }};
    const qtyText = document.getElementById("quantity");
    const totalText = document.getElementById("total");

    document.getElementById("plus").onclick = () => {
        qty++;
        update();
    };

    document.getElementById("minus").onclick = () => {
        if(qty > 1) qty--;
        update();
    };

    function update() {
        qtyText.innerText = qty;
        totalText.innerText = "Rp. " + (qty * price).toLocaleString('id-ID');
    }
</script>
@endsection
