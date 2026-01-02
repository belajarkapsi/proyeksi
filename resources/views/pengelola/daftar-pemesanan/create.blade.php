@extends('layout.pengelola')
@section('title', 'Input Pesanan Manual')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Walk-in Booking (Manual)</h1>
        <a href="{{ route('pengelola.pemesanan.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 text-red-700">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('pengelola.pemesanan.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- KOLOM KIRI: DATA TAMU & KAMAR --}}
            <div class="space-y-6">
                <div class="bg-blue-50/50 p-4 rounded-lg border border-blue-100">
                    <h3 class="font-bold text-blue-800 uppercase text-xs mb-3 border-b border-blue-200 pb-1">Data Tamu</h3>
                    <div class="space-y-4">

                        <div>
                            <label class="text-sm font-medium text-gray-700">No. HP (ID) <span class="text-red-500">*</span></label>
                            <input type="number" name="no_hp" id="no_hp" required value="{{ old('no_hp') }}"
                                class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="08..." autocomplete="off">

                            <div id="user_feedback" class="mt-2 text-xs font-bold hidden"></div>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Nama Tamu <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_tamu" id="nama_tamu" required value="{{ old('nama_tamu') }}"
                                class="w-full rounded border-gray-300 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Nama Lengkap">
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-gray-500 uppercase text-xs mb-3 border-b pb-1">Detail Kamar</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium">Pilih Kamar</label>
                            <select name="id_kamar" id="id_kamar" required class="w-full rounded border-gray-300">
                                <option value="">-- Pilih --</option>
                                @foreach($kamars as $kamar)
                                    <option value="{{ $kamar->id_kamar }}" data-harga="{{ $kamar->harga_kamar }}">
                                        {{ $kamar->no_kamar }} - {{ $kamar->tipe_kamar }} (Rp {{ number_format($kamar->harga_kamar) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium">Tgl Masuk</label>
                                <input type="date" name="tanggal_masuk" value="{{ date('Y-m-d') }}" required class="w-full rounded border-gray-300">
                            </div>
                            <div>
                                <label class="text-sm font-medium">Durasi (Malam)</label>
                                <input type="number" name="durasi" id="durasi" value="1" min="1" required class="w-full rounded border-gray-300">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN: SERVICES & TOTAL --}}
            <div class="space-y-6">
                <div>
                    <h3 class="font-bold text-gray-500 uppercase text-xs mb-3 border-b pb-1">Layanan Tambahan (Opsional)</h3>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-3 max-h-60 overflow-y-auto border border-gray-200">
                        @forelse($services as $svc)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox" name="services[]" value="{{ $svc->id }}"
                                        id="svc_{{ $svc->id }}" data-price="{{ $svc->price }}"
                                        class="service-check h-4 w-4 text-green-600 rounded border-gray-300">
                                    <label for="svc_{{ $svc->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $svc->name }} <span class="text-gray-400 text-xs">(Rp {{ number_format($svc->price) }})</span>
                                    </label>
                                </div>
                                <input type="number" name="service_qty[{{ $svc->id }}]" value="1" min="1"
                                    class="service-qty w-16 text-xs rounded border-gray-300 hidden" disabled>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic">Tidak ada layanan tersedia.</p>
                        @endforelse
                    </div>
                </div>

                <div>
                    <label class="text-sm font-medium">Status Pembayaran</label>
                    <select name="status_bayar" class="w-full rounded border-gray-300 bg-blue-100 py-2 mb-4">
                        <option value="Lunas" class="bg-gray-100">Bayar Lunas (Sekarang)</option>
                        <option value="Belum Dibayar" class="bg-gray-100">Booking Dulu (Bayar Nanti)</option>
                    </select>

                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg text-center shadow-sm">
                        <p class="text-xs text-green-800 font-bold uppercase tracking-wider">Total Estimasi</p>
                        <h2 class="text-3xl font-bold text-green-700 mt-1" id="grand_total">Rp 0</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-600 font-bold rounded-lg hover:bg-gray-50 transition">
                Reset
            </button>
            <button type="submit" class="px-6 py-3 bg-gray-900 text-white font-bold rounded-lg hover:bg-black transition shadow-lg hover:shadow-xl">
                Buat Pesanan
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- 1. HARGA KALKULASI (Sama seperti sebelumnya) ---
    const elKamar = document.getElementById('id_kamar');
    const elDurasi = document.getElementById('durasi');
    const elTotal = document.getElementById('grand_total');
    const checkboxes = document.querySelectorAll('.service-check');
    const qtyInputs = document.querySelectorAll('.service-qty');

    checkboxes.forEach((chk, index) => {
        chk.addEventListener('change', function() {
            const qtyInput = qtyInputs[index];
            if(this.checked) {
                qtyInput.classList.remove('hidden');
                qtyInput.removeAttribute('disabled');
            } else {
                qtyInput.classList.add('hidden');
                qtyInput.setAttribute('disabled', 'true');
            }
            calculate();
        });
    });

    qtyInputs.forEach(input => { input.addEventListener('input', calculate); });

    function calculate() {
        let total = 0;
        const selectedOpt = elKamar.options[elKamar.selectedIndex];
        const hargaKamar = selectedOpt.getAttribute('data-harga') ? parseInt(selectedOpt.getAttribute('data-harga')) : 0;
        const durasi = parseInt(elDurasi.value) || 0;

        total += (hargaKamar * durasi);

        checkboxes.forEach((chk, index) => {
            if(chk.checked) {
                const price = parseInt(chk.getAttribute('data-price')) || 0;
                const qty = parseInt(qtyInputs[index].value) || 1;
                total += (price * qty);
            }
        });
        elTotal.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    elKamar.addEventListener('change', calculate);
    elDurasi.addEventListener('input', calculate);

    // --- 2. AUTO CHECK USER (Fitur Baru) ---
    const hpInput = document.getElementById('no_hp');
    const namaInput = document.getElementById('nama_tamu');
    const feedback = document.getElementById('user_feedback');
    let timeout = null;

    hpInput.addEventListener('input', function() {
        const phone = this.value;

        // Reset state jika input kosong
        if(phone.length < 3) {
            feedback.classList.add('hidden');
            return;
        }

        // Debounce (tunggu user selesai ngetik 500ms)
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            fetchUser(phone);
        }, 500);
    });

    function fetchUser(phone) {
        // Panggil Route API Check (Pastikan route ini ada di web.php)
        fetch(`{{ url('/pengelola/check-penyewa') }}?no_hp=${phone}`)
            .then(response => response.json())
            .then(data => {
                feedback.classList.remove('hidden');
                if(data.exists) {
                    // USER ADA
                    feedback.innerHTML = `✅ Penyewa Terdaftar: <b>${data.nama}</b>`;
                    feedback.className = "mt-2 text-xs font-bold text-green-600 bg-green-50 p-2 rounded border border-green-200";
                    namaInput.value = data.nama; // Auto fill nama
                } else {
                    // USER BARU
                    feedback.innerHTML = `ℹ️ Penyewa Baru. Akun akan dibuat otomatis.`;
                    feedback.className = "mt-2 text-xs font-bold text-blue-600 bg-blue-50 p-2 rounded border border-blue-200";
                    // namaInput.value = ''; // Opsional: Kosongkan nama jika ingin diketik manual
                }
            })
            .catch(err => console.error(err));
    }
});
</script>
@endpush
@endsection
