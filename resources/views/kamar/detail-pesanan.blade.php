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

        {{-- SISI KIRI: Detail Harga & Timer (Background Hijau Tua) --}}
        <div class="md:w-5/12 bg-green-900 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            {{-- Dekorasi Background --}}
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#FFFFFF" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.2,-19.2,95.8,-5.3C93.5,8.6,82.1,21.5,70.9,32.2C59.7,42.9,48.8,51.4,37.3,58.4C25.8,65.4,13.7,70.9,-0.4,71.6C-14.5,72.3,-29,68.2,-41.2,60.3C-53.4,52.4,-63.3,40.7,-70.3,27.5C-77.3,14.3,-81.4,-0.4,-79.3,-14.2C-77.2,-28,-68.9,-40.9,-57.7,-50.6C-46.5,-60.3,-32.4,-66.8,-18.7,-70.3C-5,-73.8,8.7,-74.3,22.4,-74.8L36.1,-75.3Z" transform="translate(100 100)" />
                </svg>
            </div>

            <div class="relative z-10">
                <h1 class="text-2xl font-bold mb-1">Menunggu Pembayaran</h1>
                <p class="text-green-200 text-sm mb-8">Kode Pesanan: #{{ $pemesanan->id_pemesanan }}</p>

                <p class="text-green-300 text-xs uppercase tracking-widest font-semibold mb-2">Total Tagihan</p>
                <h2 class="text-4xl font-extrabold text-white mb-8">
                    Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                </h2>
            </div>

            {{-- COUNTDOWN TIMER (Design Baru) --}}
            @if($pemesanan->status == 'Belum Dibayar')
            <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 text-center">
                <p class="text-green-200 text-sm mb-2 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Sisa Waktu Bayar
                </p>
                <div id="countdown" class="text-4xl font-mono font-bold text-white tracking-widest">
                    Loading...
                </div>
            </div>
            @else
            {{-- Jika Status Sudah Berubah (Lunas/Batal) --}}
            <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 text-center">
                <span class="font-bold text-xl uppercase tracking-widest">
                    {{ $pemesanan->status }}
                </span>
            </div>
            @endif
        </div>

        {{-- SISI KANAN: Info Rekening (Background Putih) --}}
        <div class="md:w-7/12 p-8 md:p-12 bg-white flex flex-col justify-center">

            @if($pemesanan->status == 'Belum Dibayar')
                <div class="mb-8">
                    <h3 class="text-gray-800 font-bold text-xl mb-4">Metode Transfer Bank</h3>
                    <div class="flex items-start gap-4 p-5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-green-200 transition-colors">
                        <div class="bg-white p-3 rounded-xl shadow-sm border border-gray-200">
                            {{-- Icon Bank Generic --}}
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

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-blue-50 border border-blue-100 text-blue-800 text-sm flex gap-3">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p>Pesanan akan otomatis hangus jika pembayaran tidak dilakukan sebelum waktu habis.</p>
                    </div>

                    <p class="text-center text-sm text-gray-500 pt-4">
                        Sudah transfer? Konfirmasi ke admin:
                    </p>
                    <a href="https://wa.me/628123456789?text=Halo%20admin,%20saya%20sudah%20bayar%20pesanan%20{{ $pemesanan->id_pemesanan }}" target="_blank"
                        class="flex items-center justify-center gap-2 w-full py-4 px-6 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition shadow-lg hover:-translate-y-1 transform">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Konfirmasi via WhatsApp
                    </a>

                    <div class="pt-4 border-t border-gray-100 mt-4">
                        <form id="form-batal" action="{{ route('booking.batal', $pemesanan->id_pemesanan) }}" method="POST">
                            @csrf
                            <button type="button" onclick="konfirmasiBatal()"
                                class="w-full py-3 px-6 text-red-600 font-bold text-sm bg-red-50 hover:bg-red-100 rounded-xl transition border border-red-100 hover:border-red-200">
                                Batalkan Pesanan
                            </button>
                        </form>
                    </div>
                </div>

            @elseif($pemesanan->status == 'Dibatalkan')
                <div class="text-center py-10">
                    <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Dibatalkan</h2>
                    <p class="text-gray-500 mb-6">Waktu pembayaran telah habis atau pesanan dibatalkan.</p>
                    <a href="{{ route('cabang.kamar.index', $cabang->route_params) }}" class="inline-block bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-gray-800 transition">Pesan Kembali</a>
                </div>

            @elseif($pemesanan->status == 'Lunas')
                <div class="text-center py-10">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h2>
                    <p class="text-gray-500 mb-6">Terima kasih, pesanan Anda telah dikonfirmasi.</p>
                    <a href="{{ route('dashboard') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-green-700 transition">Kembali ke Dashboard</a>
                </div>
            @endif

        </div>
    </div>
</div>

{{-- SCRIPT JANTUNG (AJAX + TIMESTAMP) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        Swal.fire({
            icon: 'success',
            title: 'Tersalin',
            text: 'Nomor rekening berhasil disalin',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    }

    function konfirmasiBatal() {
        Swal.fire({
            title: 'Batalkan Pesanan?',
            text: "Apakah Anda yakin ingin membatalkan pesanan ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Merah
            cancelButtonColor: '#3085d6', // Biru
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-batal').submit();
            }
        });
    }

    @if($pemesanan->status == 'Belum Dibayar')
        // 1. GUNAKAN TIMESTAMP (Integer) DARI SERVER AGAR ZONA WAKTU SINKRON
        // expired_at di DB diubah jadi detik (Unix Timestamp) * 1000 (Milisecond)
        const expiredTimestamp = {{ \Carbon\Carbon::parse($pemesanan->expired_at)->timestamp * 1000 }};

        const countdownFunction = setInterval(function() {
            const now = new Date().getTime(); // Waktu browser user
            const distance = expiredTimestamp - now; // Selisih

            if (distance < 0) {
                clearInterval(countdownFunction);
                document.getElementById("countdown").innerHTML = "WAKTU HABIS";

                // Panggil fungsi cek status (agar update ke Dibatalkan di DB)
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

        // 2. AJAX POLLING (Cek Status ke Server setiap 5 detik)
        // Ini mencegah refresh halaman looping
        function checkStatus() {
            fetch("{{ route('booking.check_status', $pemesanan->id_pemesanan) }}")
                .then(response => response.json())
                .then(data => {
                    // Jika status berubah jadi 'Dibatalkan' atau 'Lunas', baru reload halaman
                    if (data.status === 'Dibatalkan' || data.status === 'Lunas') {
                        window.location.reload();
                    }
                })
                .catch(error => console.error('Error checking status:', error));
        }

        // Jalankan polling setiap 5 detik
        setInterval(checkStatus, 5000);
    @endif
</script>
@endsection
