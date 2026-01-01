@extends('layout.pengelola')
@section('title', 'Laporan & Analitik')

@section('content')
<div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- HEADER & FILTER --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Operasional</h1>
            <p class="text-sm text-gray-500">Cabang: {{ $cabang->nama_cabang }} ({{ ucfirst($cabang->kategori_cabang) }})</p>
        </div>

        {{-- FORM FILTER AJAX --}}
        <form id="filterForm" class="flex flex-col sm:flex-row gap-3 bg-white p-2 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-gray-500 uppercase px-2">Periode:</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $tgglMulai }}" class="text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                <span class="text-gray-400">-</span>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $tgglSelesai }}" class="text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
            </div>
            <button type="submit" id="btnFilter" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition flex items-center justify-center">
                <i class="fas fa-filter mr-1"></i>
                <span id="btnText">Tampilkan</span>
            </button>

            <a href="{{ route('pengelola.laporan.cetak', ['tanggal_mulai' => $tgglMulai, 'tanggal_selesai' => $tgglSelesai]) }}" id="btnPdf" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-900 transition flex items-center justify-center">
                <i class="fas fa-print mr-1"></i> PDF
            </a>
        </form>
    </div>

    {{-- 1. SECTION FINANCIAL REPORT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Card Total Omzet --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-1">
            <h3 class="text-gray-500 text-sm font-medium uppercase mb-2">Total Pendapatan</h3>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold text-green-600">Rp <span id="valTotalPendapatan">{{ number_format($totalPendapatan, 0, ',', '.') }}</span></span>
            </div>
            <p class="text-xs text-gray-400 mt-2">Periode <span id="valPeriode">{{ \Carbon\Carbon::parse($tgglMulai)->format('d M') }} - {{ \Carbon\Carbon::parse($tgglSelesai)->format('d M Y') }}</span></p>

            @if($isVilla)
            <div class="mt-6 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Dari Sewa Kamar</span>
                    <span class="font-bold">Rp <span id="valPendapatanKamar">{{ number_format($pendapatanKamar, 0, ',', '.') }}</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div id="barKamar" class="bg-green-500 h-1.5 rounded-full" style="width: {{ $totalPendapatan > 0 ? ($pendapatanKamar/$totalPendapatan)*100 : 0 }}%"></div>
                </div>

                <div class="flex justify-between text-sm mt-2">
                    <span class="text-gray-600">Dari Layanan (Service)</span>
                    <span class="font-bold">Rp <span id="valPendapatanService">{{ number_format($pendapatanService, 0, ',', '.') }}</span></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-1.5">
                    <div id="barService" class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $totalPendapatan > 0 ? ($pendapatanService/$totalPendapatan)*100 : 0 }}%"></div>
                </div>
            </div>
            @endif
        </div>

        {{-- Grafik Tren --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
            <h3 class="text-gray-800 font-bold mb-4">Tren Pendapatan Harian</h3>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- 2. SECTION OPERATIONAL --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- A. Analisa Kamar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-bed text-green-600 mr-2"></i> Pemesanan Kamar</h3>
            </div>
            <div class="p-6">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                        <tr><th class="px-3 py-2">Kamar</th><th class="px-3 py-2 text-center">Terpesan</th><th class="px-3 py-2 text-right">Omzet</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="tbodyTopRooms">
                        @include('pengelola.laporan.partials.tabel-kamar', ['topKamar' => $topKamar])
                    </tbody>
                </table>
            </div>
        </div>

        {{-- B. Analisa Service (KHUSUS VILLA) --}}
        @if($isVilla)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-800"><i class="fas fa-concierge-bell text-blue-600 mr-2"></i> Layanan Terlaris</h3>
            </div>
            <div class="p-6">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                        <tr><th class="px-3 py-2">Nama Layanan</th><th class="px-3 py-2 text-center">Terjual</th><th class="px-3 py-2 text-right">Total</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="tbodyTopServices">
                        @include('pengelola.laporan.partials.tabel-service', ['topServices' => $topServices])
                    </tbody>
                </table>
            </div>
        </div>
        @else
        {{-- Placeholder Kost --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-center text-center">
            <div>
                <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-home text-green-600 text-2xl"></i></div>
                <h3 class="font-bold text-gray-800">Analitik Kamar Kost</h3>
            </div>
        </div>
        @endif
    </div>

    {{-- 3. SECTION STATUS & CANCEL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 text-center">Komposisi Status Pesanan</h3>
            <div class="h-64 relative">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fas fa-user-times text-red-500"></i> Analisa Pembatalan</h3>
            <div class="space-y-4" id="listCancelReasons">
                @include('pengelola.laporan.partials.alasan-batal', ['alasanBatal' => $alasanBatal])
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // --- INIT CHART INSTANCES (Agar bisa di-update via AJAX) ---
    let trendChartInstance = null;
    let statusChartInstance = null;

    // Fungsi Render Chart Trend
    function renderTrendChart(labels, data) {
        const ctx = document.getElementById('trendChart').getContext('2d');
        if (trendChartInstance) trendChartInstance.destroy(); // Hapus chart lama jika ada

        trendChartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: data,
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: function(value) { return 'Rp ' + (value/1000) + 'k'; } } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Fungsi Render Chart Status
    function renderStatusChart(labels, data) {
        const ctx = document.getElementById('statusChart').getContext('2d');
        if (statusChartInstance) statusChartInstance.destroy();

        const statusColors = { 'Lunas': '#16a34a', 'Selesai': '#2563eb', 'Dibatalkan': '#dc2626', 'Belum Dibayar': '#ca8a04' };
        const bgColors = labels.map(label => statusColors[label] || '#9ca3af');

        statusChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{ data: data, backgroundColor: bgColors, borderWidth: 0, hoverOffset: 4 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } },
                cutout: '70%',
            }
        });
    }

    // --- RENDER AWAL SAAT PAGE LOAD ---
    renderTrendChart({!! json_encode($chartTrendLabels) !!}, {!! json_encode($chartTrendValues) !!});
    renderStatusChart({!! json_encode($chartStatusLabels) !!}, {!! json_encode($chartStatusValues) !!});

    // --- LOGIKA AJAX FILTER ---
    const filterForm = document.getElementById('filterForm');
    const btnFilter = document.getElementById('btnFilter');
    const btnText = document.getElementById('btnText');

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // UI Loading State
        btnFilter.disabled = true;
        btnText.innerText = 'Memuat...';
        btnFilter.classList.add('opacity-75');

        // Ambil data form
        const tanggal_mulai = document.getElementById('tanggal_mulai').value;
        const tanggal_selesai = document.getElementById('tanggal_selesai').value;

        // Fetch API
        fetch(`{{ route('pengelola.laporan.index') }}?tanggal_mulai=${tanggal_mulai}&tanggal_selesai=${tanggal_selesai}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Penanda AJAX untuk Laravel
            }
        })
        .then(response => response.json())
        .then(data => {
            // 1. Update Angka Text
            document.getElementById('valTotalPendapatan').innerText = data.totalPendapatan;
            document.getElementById('valPeriode').innerText = data.periodeText;

            // Update Villa specific (cek if exist first)
            const elKamar = document.getElementById('valPendapatanKamar');
            if(elKamar) {
                elKamar.innerText = data.pendapatanKamar;
                document.getElementById('valPendapatanService').innerText = data.pendapatanService;
                document.getElementById('barKamar').style.width = data.widthKamar + '%';
                document.getElementById('barService').style.width = data.widthService + '%';
            }

            // 2. Update Charts
            renderTrendChart(data.trendLabels, data.trendValues);
            renderStatusChart(data.statusLabels, data.statusValues);

            // 3. Update HTML Tables (Partials)
            document.getElementById('tbodyTopRooms').innerHTML = data.htmlTopRooms;
            if(data.htmlTopServices) {
                document.getElementById('tbodyTopServices').innerHTML = data.htmlTopServices;
            }
            document.getElementById('listCancelReasons').innerHTML = data.htmlCancelReasons;

            const newPdfUrl = `{{ route('pengelola.laporan.cetak') }}?tanggal_mulai=${tanggal_mulai}&tanggal_selesai=${tanggal_selesai}`;
            document.getElementById('btnPdf').setAttribute('href', newPdfUrl);

        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat data laporan.');
        })
        .finally(() => {
            // Restore UI
            btnFilter.disabled = false;
            btnText.innerText = 'Tampilkan';
            btnFilter.classList.remove('opacity-75');
        });
    });
</script>
@endpush
@endsection
