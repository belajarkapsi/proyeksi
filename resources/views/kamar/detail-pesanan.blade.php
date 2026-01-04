@extends('layout.master')
@section('title', 'Pembayaran')

@section('content')
@php
    // Sesuai Model Pemesanan, nama relasinya adalah items()
    $item = $pemesanan->items->first();
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sticky top-20 z-30 pointer-events-none">
    <a href="{{ route('booking.riwayat') }}" class="pointer-events-auto inline-flex items-center gap-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-gray-200 text-green-700 text-sm font-semibold rounded-full shadow-sm hover:shadow-md hover:bg-green-600 hover:text-white transition-all duration-300 group transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Riwayat Pesanan
    </a>
</div>

<div class="min-h-[70vh] bg-gray-50 flex items-start justify-center px-4 pb-8">
    <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">

        {{-- SISI KIRI: Detail Harga, INFO PESANAN, & Timer --}}
        <div class="md:w-5/12 bg-green-900 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#FFFFFF" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.2,-19.2,95.8,-5.3C93.5,8.6,82.1,21.5,70.9,32.2C59.7,42.9,48.8,51.4,37.3,58.4C25.8,65.4,13.7,70.9,-0.4,71.6C-14.5,72.3,-29,68.2,-41.2,60.3C-53.4,52.4,-63.3,40.7,-70.3,27.5C-77.3,14.3,-81.4,-0.4,-79.3,-14.2C-77.2,-28,-68.9,-40.9,-57.7,-50.6C-46.5,-60.3,-32.4,-66.8,-18.7,-70.3C-5,-73.8,8.7,-74.3,22.4,-74.8L36.1,-75.3Z" transform="translate(100 100)" />
                </svg>
            </div>

            <div class="relative z-10">
                @if($pemesanan->status == 'Dibatalkan')
                    <h1 class="text-2xl font-bold mb-1 text-red-200">Pesanan Dibatalkan</h1>
                @elseif($pemesanan->status == 'Lunas')
                    <h1 class="text-2xl font-bold mb-1 text-green-200">Pembayaran Lunas</h1>
                @else
                    <h1 class="text-2xl font-bold mb-1">Menunggu Pembayaran</h1>
                @endif
                <p class="text-green-200 text-sm mb-6">Kode Pesanan: #{{ $pemesanan->id_pemesanan }}</p>

                {{-- INFO RINGKASAN PESANAN --}}
                <div class="space-y-4 mb-8 border-y border-white/10 py-4">
                    {{-- Info Cabang --}}
                    <div>
                        <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-1">Lokasi Cabang</p>
                        <p class="text-sm font-bold">{{ $pemesanan->cabang->nama_cabang ?? 'Nama Cabang Tidak Ditemukan' }}</p>
                        <p class="text-xs text-green-200/70">{{ $pemesanan->cabang->alamat ?? '' }}</p>
                    </div>

                    {{-- Info Unit/Kamar --}}
                    <div>
                        <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-1">Unit Pesanan</p>
                        <p class="text-sm font-bold">{{ $item->kamar->no_kamar ?? 'Detail Kamar' }}</p>
                    </div>

                    {{-- Info Waktu --}}
                    <div class="flex justify-between gap-4">
                        <div>
                            <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-1">Check-in</p>
                            <p class="text-sm">{{ $item && $item->waktu_checkin ? $item->waktu_checkin->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-1">Check-out</p>
                            <p class="text-sm">{{ $item && $item->waktu_checkout ? $item->waktu_checkout->format('d M Y') : '-' }}</p>
                        </div>
                    </div>

                    {{-- Info Layanan Service (Hanya tampil jika ada) --}}
                    @if($pemesanan->service && $pemesanan->service->count() > 0)
                    <div class="pt-2">
                        <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-2">Layanan Tambahan</p>
                        <div class="space-y-1">
                            @foreach($pemesanan->service as $svc)
                                <div class="flex justify-between text-xs bg-white/5 p-2 rounded border border-white/10">
                                    <span>{{ $svc->service->name }}</span>
                                    <span class="font-mono">
                                        Rp {{ number_format($svc->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-2">Total Tagihan</p>
                <h2 class="text-4xl font-extrabold text-white mb-2">
                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                </h2>
            </div>

            {{-- STATUS BOX / TIMER --}}
            @if($pemesanan->status == 'Belum Dibayar')
            <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 text-center mt-4">
                <p class="text-green-200 text-sm mb-2 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Sisa Waktu Bayar
                </p>
                <div id="countdown" class="text-4xl font-mono font-bold text-white tracking-widest">Loading...</div>
            </div>
            @else
            <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 text-center mt-4">
                <span class="font-bold text-xl uppercase tracking-widest {{ $pemesanan->status == 'Dibatalkan' ? 'text-red-300' : 'text-white' }}">
                    {{ $pemesanan->status }}
                </span>
            </div>
            @endif
        </div>

        {{-- SISI KANAN: Aksi Pembayaran --}}
        <div class="md:w-7/12 p-8 md:p-12 bg-white flex flex-col justify-center">

            @if($pemesanan->status == 'Belum Dibayar')
                @if($pemesanan->pembayaran)
                    <div class="text-center py-6">
                        <div class="bg-blue-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Bukti Pembayaran Terkirim</h3>
                        <p class="text-gray-500 text-sm mt-2 mb-6">Sedang diverifikasi. Status akan otomatis berubah menjadi <b>LUNAS</b>.</p>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-left mb-6 flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden shrink-0 cursor-pointer" onclick="lihatBukti('{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}')">
                                <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="text-sm">
                                <p class="font-medium text-gray-700">Bukti Transfer</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pemesanan->pembayaran->waktu_pembayaran)->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        <a href="https://wa.me/081241334241?text=Halo%20Admin,%20saya%20sudah%20upload%20bukti%20pembayaran%20untuk%20Pesanan%20%23{{ $pemesanan->id_pemesanan }}" target="_blank"
                            class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-green-100 text-green-700 font-bold rounded-xl hover:bg-green-200 transition mb-4">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766 0-3.18-2.587-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.512-2.96-2.626-.088-.113-.716-.953-.716-1.819 0-.866.454-1.292.614-1.457.16-.165.346-.207.461-.207s.23.003.332.007c.108.005.253-.041.396.304.148.355.506 1.233.551 1.324.045.091.075.197.015.318-.06.121-.09.197-.181.303-.09.106-.189.236-.269.318-.09.091-.184.19-.08.368.104.178.464.767.995 1.24.685.611 1.261.801 1.441.89.179.089.284.075.39-.045.105-.121.451-.523.571-.7.121-.178.241-.148.406-.088.165.06 1.05.495 1.231.585s.301.135.346.21c.045.075.045.435-.099.84z"/></svg>
                                Hubungi Admin
                        </a>
                        <button onclick="document.getElementById('upload-section').classList.remove('hidden'); this.classList.add('hidden');" class="text-sm text-blue-600 hover:underline">Upload ulang bukti pembayaran</button>
                    </div>
                @endif

                <div id="upload-section" class="{{ $pemesanan->pembayaran ? 'hidden' : '' }}">
                    <div class="mb-8">
                        <h3 class="text-gray-800 font-bold text-xl mb-4">Metode Transfer Bank</h3>
                        <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-200 text-center">
                                <span class="text-blue-600 font-bold italic tracking-tighter text-lg">{{ $rekening['bank'] }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 font-medium tracking-tight">Transfer Manual ke:</p>
                                <div class="flex items-center gap-3">
                                    <p class="text-2xl font-bold text-gray-900 tracking-wide" id="rek-number">{{ $rekening['nomor'] }}</p>
                                    <button onclick="copyToClipboard('{{ str_replace(' ', '', $rekening['nomor']) }}')" class="text-green-600 hover:text-green-800 text-xs font-bold bg-green-50 px-2 py-1 rounded transition">
                                        SALIN
                                    </button>
                                </div>
                                <p class="text-xs text-gray-400">a.n {{ $rekening['atas_nama'] }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('booking.upload_bukti', $pemesanan->id_pemesanan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-green-400 transition cursor-pointer group">
                            <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)" required>
                            <div id="upload-placeholder">
                                <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-green-500 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <p class="mt-2 text-sm text-gray-600">Klik untuk upload bukti bayar</p>
                                <p class="mt-1 text-xs text-gray-400 italic">Format: PNG, JPG, JPEG (Maks 2MB)</p>
                            </div>
                            <div id="image-preview-container" class="hidden mt-2">
                                <img id="image-preview" src="#" class="mx-auto max-h-32 rounded-lg shadow-sm">
                                <p class="text-xs text-green-600 mt-2 font-bold">Ganti Gambar?</p>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-md">Kirim Bukti Pembayaran</button>
                    </form>

                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <form id="form-batal" action="{{ route('booking.batal', $pemesanan->id_pemesanan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="alasan_batal" id="input_alasan_batal">
                            <button type="button" onclick="konfirmasiBatal()" class="text-red-500 text-sm font-medium hover:underline">Batalkan Pesanan Ini</button>
                        </form>
                    </div>
                </div>

            @elseif($pemesanan->status == 'Dibatalkan')
                <div class="text-center py-6">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 uppercase tracking-tight">Dibatalkan</h2>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 my-4 text-left italic">
                        <p class="text-xs font-bold text-red-500 uppercase mb-1 tracking-wider">Alasan Pembatalan:</p>
                        <p class="text-sm text-gray-700">"{{ $pemesanan->alasan_batal ?? 'Dibatalkan otomatis oleh sistem.' }}"</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-gray-900 text-white px-8 py-3 rounded-xl font-bold hover:bg-gray-800 transition shadow-xl">Kembali ke Beranda</a>
                </div>

            @elseif($pemesanan->status == 'Lunas')
                <div class="text-center py-10">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2 tracking-tight">Pembayaran Berhasil!</h2>
                    <p class="text-gray-500 mb-8 text-sm">Terima kasih, pembayaran Anda telah kami konfirmasi.</p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('booking.cetak_invoice', $pemesanan->id_pemesanan) }}"
                        target="_blank"
                        class="bg-blue-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-blue-700 transition flex items-center gap-2 shadow-lg shadow-blue-100">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                            </svg>
                            Invoice
                        </a>
                        <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-700 px-8 py-3.5 rounded-xl font-bold hover:bg-gray-200 transition">Ke Beranda</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyToClipboard(text) {
        if (!navigator.clipboard) {
            const el = document.createElement('textarea'); el.value = text; document.body.appendChild(el); el.select(); document.execCommand('copy'); document.body.removeChild(el);
            Swal.fire({ icon: 'success', title: 'Tersalin', toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
            return;
        }
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({ icon: 'success', title: 'Tersalin', toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 });
        });
    }

    function konfirmasiBatal() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Anda yakin ingin membatalkan pesanan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Alasan Pembatalan',
                    input: 'textarea',
                    inputPlaceholder: 'Tulis alasan pembatalan Anda...',
                    showCancelButton: true,
                    confirmButtonText: 'Kirim & Batalkan',
                    confirmButtonColor: '#d33',
                    inputValidator: (value) => { if (!value) { return 'Wajib memberikan alasan!' } }
                }).then((res) => {
                    if (res.isConfirmed) {
                        document.getElementById('input_alasan_batal').value = res.value;
                        document.getElementById('form-batal').submit();
                    }
                });
            }
        });
    }

    function lihatBukti(urlGambar) {
        Swal.fire({
            imageUrl: urlGambar,
            imageWidth: '100%',
            imageHeight: 'auto',
            showConfirmButton: false,
            backdrop: `rgba(0,0,0,0.8)`
        });
    }

    @if($pemesanan->status == 'Belum Dibayar' && $pemesanan->expired_at)
        const expiredTimestamp = {{ \Carbon\Carbon::parse($pemesanan->expired_at)->timestamp * 1000 }};
        const countdownFunction = setInterval(function() {
            const now = new Date().getTime();
            const distance = expiredTimestamp - now;
            if (distance < 0) {
                clearInterval(countdownFunction);
                document.getElementById("countdown").innerHTML = "WAKTU HABIS";
                location.reload();
                return;
            }
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("countdown").innerHTML =
                (hours < 10 ? "0" + hours : hours) + ":" +
                (minutes < 10 ? "0" + minutes : minutes) + ":" +
                (seconds < 10 ? "0" + seconds : seconds);
        }, 1000);
    @endif

    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('image-preview');
            output.src = reader.result;
            document.getElementById('image-preview-container').classList.remove('hidden');
            document.getElementById('upload-placeholder').classList.add('hidden');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
@endsection
