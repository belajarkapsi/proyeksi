@extends('layout.master')
@section('title', 'Pembayaran')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sticky top-20 z-30 pointer-events-none">
    <a href="{{ route('booking.riwayat') }}" class="pointer-events-auto inline-flex items-center gap-2 px-5 py-2.5 bg-white/90 backdrop-blur-sm border border-gray-200 text-green-700 text-sm font-semibold rounded-full shadow-sm hover:shadow-md hover:bg-green-600 hover:text-white transition-all duration-300 group transform hover:-translate-y-0.5">
        <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Riwayat Pesanan
    </a>
</div>

<div class="min-h-[70vh] bg-gray-50 flex items-start justify-center px-4 pt-8 pb-8">
    <div class="max-w-5xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">

        {{-- SISI KIRI: Detail Harga & Timer --}}
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

                <p class="text-green-200 text-sm mb-8">Kode Pesanan: #{{ $pemesanan->id_pemesanan }}</p>

                <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-2">Total Tagihan</p>
                <h2 class="text-4xl font-extrabold text-white mb-8">
                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                </h2>
            </div>

            {{-- STATUS BOX / TIMER --}}
            @if($pemesanan->status == 'Belum Dibayar')
            <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 text-center">
                <p class="text-green-200 text-sm mb-2 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Sisa Waktu Bayar
                </p>
                <div id="countdown" class="text-4xl font-mono font-bold text-white tracking-widest">Loading...</div>
            </div>
            @else
            <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 text-center">
                <span class="font-bold text-xl uppercase tracking-widest {{ $pemesanan->status == 'Dibatalkan' ? 'text-red-300' : 'text-white' }}">
                    {{ $pemesanan->status }}
                </span>
            </div>
            @endif
        </div>

        {{-- SISI KANAN: Info & Action --}}
        <div class="md:w-7/12 p-8 md:p-12 bg-white flex flex-col justify-center">

            @if($pemesanan->status == 'Belum Dibayar')

                @if($pemesanan->pembayaran)
                    {{-- A. JIKA SUDAH UPLOAD BUKTI --}}
                    <div class="text-center py-6">
                        <div class="bg-blue-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Bukti Pembayaran Terkirim</h3>
                        <p class="text-gray-500 text-sm mt-2 mb-6">
                            Terima kasih! Bukti pembayaran Anda sedang diverifikasi oleh pengelola. <br>
                            Status pesanan akan berubah menjadi <b>LUNAS</b> setelah diverifikasi.
                        </p>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 text-left mb-4">
                            <p class="text-xs text-gray-400 uppercase font-bold mb-2">File Terlampir:</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg overflow-hidden shrink-0 cursor-pointer hover:opacity-80 transition"
                                    onclick="lihatBukti('{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}')"
                                    title="Klik untuk memperbesar">
                                    <img src="{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}" class="w-full h-full object-cover">
                                </div>
                                <div class="text-sm">
                                    <p class="font-medium text-gray-700">Bukti Transfer</p>
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pemesanan->pembayaran->waktu_pembayaran)->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <a href="https://wa.me/628123456789?text=Halo%20Admin,%20saya%20sudah%20upload%20bukti%20pembayaran%20untuk%20Pesanan%20%23{{ $pemesanan->id_pemesanan }}.%20Mohon%20segera%20diverifikasi.%20Terima%20kasih." target="_blank"
                            class="flex items-center justify-center gap-2 w-full py-3 px-6 bg-green-100 text-green-700 font-bold rounded-xl hover:bg-green-200 transition mb-4 border border-green-200">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                Hubungi Admin (WhatsApp)
                        </a>

                        {{-- Opsi Upload Ulang (Revisi) --}}
                        <button onclick="document.getElementById('upload-section').classList.remove('hidden'); this.classList.add('hidden');"
                            class="text-sm text-blue-600 hover:underline">
                            Ada kesalahan? Upload ulang bukti
                        </button>
                    </div>
                @endif

                {{-- B. FORM UPLOAD (Muncul jika belum upload atau klik upload ulang) --}}
                <div id="upload-section" class="{{ $pemesanan->pembayaran ? 'hidden' : '' }}">
                    <div class="mb-8">
                        <h3 class="text-gray-800 font-bold text-xl mb-4">Metode Transfer Bank</h3>
                        <div class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-green-200 transition-colors">
                            <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-200">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-500 font-medium">Bank Central Asia (BCA)</p>
                                <div class="flex items-center gap-3 mt-1">
                                    <p class="text-2xl font-bold text-gray-900 tracking-wide" id="rek-number">123 456 7890</p>
                                    <button onclick="copyToClipboard('1234567890')" class="text-green-600 hover:text-green-800 text-sm font-bold bg-green-50 px-3 py-1 rounded-lg transition-colors">
                                        Salin
                                    </button>
                                </div>
                                <p class="text-sm text-gray-400 mt-1">a.n Pondok Siti Hajar</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-6">
                        <h3 class="text-gray-800 font-bold text-lg mb-2">Upload Bukti Pembayaran</h3>
                        <p class="text-sm text-gray-500 mb-4">Silakan transfer sesuai nominal lalu upload struk/bukti transfer di bawah ini.</p>

                        <form action="{{ route('booking.upload_bukti', $pemesanan->id_pemesanan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            {{-- Input File Custom --}}
                            <div class="relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 hover:border-green-400 transition cursor-pointer group">
                                <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(event)" required>

                                <div id="upload-placeholder">
                                    <svg class="mx-auto h-10 w-10 text-gray-400 group-hover:text-green-500 transition" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 group-hover:text-green-600">Klik untuk upload gambar</p>
                                    <p class="mt-1 text-xs text-gray-400">PNG, JPG, JPEG (Max 2MB)</p>
                                </div>

                                {{-- Image Preview --}}
                                <div id="image-preview-container" class="hidden mt-2">
                                    <img id="image-preview" src="#" alt="Preview" class="mx-auto max-h-48 rounded-lg shadow-sm">
                                    <p class="text-xs text-green-600 mt-2 font-bold">Ganti Gambar?</p>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-6 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-md hover:-translate-y-1 transform flex justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                Kirim Bukti Pembayaran
                            </button>
                        </form>
                    </div>

                    {{-- Tombol Batal --}}
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <form id="form-batal" action="{{ route('booking.batal', $pemesanan->id_pemesanan) }}" method="POST">
                            @csrf
                            <input type="hidden" name="alasan_batal" id="input_alasan_batal">
                            <button type="button" onclick="konfirmasiBatal()" class="text-red-500 text-sm font-medium hover:text-red-700 hover:underline">
                                Batalkan Pesanan Ini
                            </button>
                        </form>
                    </div>
                </div>

            @elseif($pemesanan->status == 'Dibatalkan')
                {{-- ----- JIKA DIBATALKAN ----- --}}
                <div class="text-center py-6">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Dibatalkan</h2>

                    {{-- ALERT ALASAN PEMBATALAN --}}
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 my-6 text-left max-w-md mx-auto relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-red-400"></div>
                        <p class="text-xs font-bold text-red-500 uppercase tracking-wide mb-1">Alasan Pembatalan:</p>
                        <p class="text-sm text-gray-700 italic">
                            "{{ $pemesanan->alasan_batal ?? 'Pemesanan Dibatalkan Karena Waktu Habis / Dibatalkan Sistem.' }}"
                        </p>
                        <p class="text-xs text-gray-400 mt-2 text-right">
                            {{ $pemesanan->cancelled_at ? \Carbon\Carbon::parse($pemesanan->cancelled_at)->format('d M Y H:i') : '-' }}
                        </p>
                    </div>

                    <p class="text-gray-500 mb-6 text-sm">Anda dapat melakukan pemesanan ulang untuk kamar ini atau mencari kamar lain.</p>
                    <a href="{{ isset($cabang) ? route('cabang.kamar.index', $cabang->route_params) : route('dashboard') }}" class="inline-block bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition">Pesan Kembali</a>
                </div>

            @elseif($pemesanan->status == 'Lunas')
                {{-- ----- JIKA LUNAS ----- --}}
                <div class="text-center py-10">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h2>
                    <p class="text-gray-500 mb-6">Terima kasih, pesanan Anda telah dikonfirmasi.</p>

                    {{-- Lihat Bukti Bayar --}}
                    @if($pemesanan->pembayaran)
                        <div class="mb-6">
                            <button type="button"
                                onclick="lihatBukti('{{ asset('storage/' . $pemesanan->pembayaran->bukti_pembayaran) }}')"
                                class="text-sm text-green-600 font-medium hover:text-green-800 hover:underline flex items-center justify-center gap-2 mx-auto transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Lihat Bukti Pembayaran
                            </button>
                        </div>
                    @endif
                    {{-- Tombol Cetak Invoice - Sekarang berwarna BIRU --}}
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-6">
                    {{-- Tombol Cetak Invoice - Sekarang berwarna BIRU --}}
                    <a href="{{ route('booking.cetak_invoice', $pemesanan->id_pemesanan) }}" 
                        class="w-full sm:w-52 inline-flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Cetak Invoice
                    </a>

                    {{-- Tombol Dashboard - Warna HIJAU (Sesuai tema Anda) --}}
                    <a href="{{ route('dashboard') }}" 
                        class="w-full sm:w-52 inline-flex items-center justify-center gap-2 bg-green-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-green-700 transition shadow-lg shadow-green-100 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ke Dashboard
                    </a>
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

    // --- LOGIKA 2 TAHAP KONFIRMASI BATAL ---
    function konfirmasiBatal() {
        // Tahap 1: Konfirmasi Ya/Tidak
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                // Tahap 2: Input Alasan
                Swal.fire({
                    title: 'Alasan Pembatalan',
                    input: 'textarea',
                    inputLabel: 'Mohon berikan alasan pembatalan Anda',
                    inputPlaceholder: 'Tulis alasan Anda disini...',
                    inputAttributes: {
                        'aria-label': 'Tulis alasan Anda disini'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Kirim & Batalkan',
                    cancelButtonText: 'Kembali',
                    confirmButtonColor: '#d33',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Anda harus menuliskan alasan pembatalan!'
                        }
                    }
                }).then((res) => {
                    if (res.isConfirmed) {
                        // Masukkan alasan ke input hidden dan submit form
                        document.getElementById('input_alasan_batal').value = res.value;
                        document.getElementById('form-batal').submit();
                    }
                });
            }
        });
    }

    function lihatBukti(urlGambar) {
        Swal.fire({
            title: 'Bukti Pembayaran',
            imageUrl: urlGambar,
            imageAlt: 'Bukti Pembayaran',
            imageWidth: '100%',
            imageHeight: 'auto',
            showCloseButton: true,
            showConfirmButton: false,
            width: 600,
            padding: '1em',
            background: '#fff',
            backdrop: `
                rgba(0,0,0,0.8)
            `
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
                checkStatus();
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

        function checkStatus() {
            const url = "{{ route('booking.check_status', $pemesanan->id_pemesanan) }}";
            fetch(url, { cache: "no-store" })
                .then(response => {
                    if (!response.ok) throw new Error('HTTP status ' + response.status);
                    return response.json();
                })
                .then(data => {
                    if (!data || !data.status) return;
                    if (data.status === 'Dibatalkan' || data.status === 'Lunas') {
                        window.location.reload();
                    }
                })
                .catch(error => { console.debug('checkStatus fetch error:', error); });
        }
        setInterval(checkStatus, 5000);

        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('image-preview');
                output.src = reader.result;

                // Tampilkan container preview, sembunyikan placeholder
                document.getElementById('image-preview-container').classList.remove('hidden');
                document.getElementById('upload-placeholder').classList.add('hidden');
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    @endif
</script>
@endpush
@endsection
