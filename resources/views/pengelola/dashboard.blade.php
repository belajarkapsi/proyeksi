@extends('layout.pengelola')
@section('title', 'Dashboard Pengelola')

@section('header')
<div class="bg-linear-to-r from-green-600 to-green-500 py-6 px-4 sm:px-6 lg:px-8 rounded-lg mb-6">
    <h1 class="text-2xl font-bold text-white">
        Selamat Datang, {{ $pengelola->nama_lengkap }}
    </h1>
    @if($cabang)
        <p class="mt-1 text-green-100 text-sm">
            Anda mengelola cabang:
            <span class="font-semibold">
                {{ $cabang->nama_cabang }} ({{ ucfirst($cabang->kategori_cabang) }})
            </span>
            — {{ $cabang->lokasi }}
        </p>
    @else
        <p class="mt-1 text-yellow-100 text-sm">
            Cabang belum ditentukan untuk akun Anda.
        </p>
    @endif
</div>
@endsection

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-blue-50 text-blue-600 rounded-full">
                <i class="fas fa-bed fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Kamar</p>
                <p class="text-2xl font-bold text-gray-800" id="val-kamar">0</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-full">
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Pelanggan</p>
                <p class="text-2xl font-bold text-gray-800" id="val-penyewa">0</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center space-x-4">
            <div class="p-3 bg-purple-50 text-purple-600 rounded-full">
                <i class="fas fa-shopping-cart fa-2x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-800" id="val-pesanan">0</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-6 flex items-center space-x-4 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-orange-100 rounded-bl-full -mr-8 -mt-8 z-0"></div>
            <div class="p-3 bg-orange-50 text-orange-600 rounded-full z-10">
                <i class="fas fa-clock fa-2x"></i>
            </div>
            <div class="z-10">
                <p class="text-sm text-gray-500 font-medium">Perlu Konfirmasi</p>
                <p class="text-2xl font-bold text-orange-600 animate-pulse" id="val-belum-konfirmasi">0</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

        {{-- CHART 1: TREN PESANAN (Line) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <div class="p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                <h3 class="font-bold text-gray-700"><i class="fas fa-chart-line text-green-500 mr-2"></i> Tren Pesanan</h3>
            </div>
            <div class="p-4 flex-1">
                <div class="relative h-64 w-full">
                    <canvas id="chartPenyewa"></canvas>
                </div>
            </div>
        </div>

        {{-- CHART 2: UTILISASI LAYANAN (Bar Chart - BARU) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col">
            <div class="p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                <h3 class="font-bold text-gray-700"><i class="fas fa-chart-bar text-blue-500 mr-2"></i> Utilisasi Layanan</h3>
            </div>
            <div class="p-4 flex-1">
                <div class="relative h-64 w-full">
                    <canvas id="chartBarLayanan"></canvas>
                </div>
            </div>
        </div>

        {{-- CHART 3: DISTRIBUSI TIPE KAMAR (HANYA UNTUK KOST) --}}
        @if(Str::lower($cabang->kategori_cabang) === 'kost')
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col lg:col-span-2">
            <div class="p-4 border-b border-gray-100 bg-gray-50/50 rounded-t-xl">
                <h3 class="font-bold text-gray-700"><i class="fas fa-chart-pie text-purple-500 mr-2"></i> Distribusi Tipe Kamar (Kost)</h3>
            </div>
            <div class="p-4 flex-1">
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="chartTipeKamar"></canvas>
                </div>
            </div>
        </div>
        @endif

    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // --- 1. INIT CHART TREN (LINE) ---
        const ctxPenyewa = document.getElementById('chartPenyewa').getContext('2d');
        const chartPenyewa = new Chart(ctxPenyewa, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Pesanan',
                    data: [],
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } }
        });

        // --- 2. INIT CHART BAR UTILISASI (BARU) ---
        const ctxBar = document.getElementById('chartBarLayanan').getContext('2d');
        const chartBar = new Chart(ctxBar, {
            type: 'bar',
            data: {
                // Label Sumbu X
                labels: ['Unit Kamar', 'Service Villa'],
                datasets: [
                    {
                        label: 'Total Layanan', // Bar Kiri
                        data: [],
                        backgroundColor: '#3b82f6', // Biru
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    },
                    {
                        label: 'Sedang Dipesan', // Bar Kanan
                        data: [],
                        backgroundColor: '#ef4444', // Merah
                        borderRadius: 4,
                        barPercentage: 0.6,
                        categoryPercentage: 0.8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                }
            }
        });

        // --- 3. INIT CHART TIPE KAMAR (DOUGHNUT) - JIKA ADA ---
        let chartTipe = null;
        const elemTipe = document.getElementById('chartTipeKamar');
        if (elemTipe) { // Cek apakah elemen ada (karena di-IF blade)
            const ctxTipe = elemTipe.getContext('2d');
            chartTipe = new Chart(ctxTipe, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: ['#8b5cf6', '#f59e0b', '#10b981', '#3b82f6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom' } } }
            });
        }

        // --- FUNGSI AJAX UPDATE ---
        function updateDashboard() {
            fetch("{{ route('pengelola.dashboard.data') }}")
                .then(response => response.json())
                .then(data => {
                    // Update Angka Statistik
                    document.getElementById('val-kamar').innerText = data.total_kamar;
                    document.getElementById('val-penyewa').innerText = data.total_penyewa;
                    document.getElementById('val-pesanan').innerText = data.total_pesanan;

                    // Update Angka Card Baru
                    const elBelum = document.getElementById('val-belum-konfirmasi');
                    if(elBelum) elBelum.innerText = data.total_belum_konfirmasi;

                    // Update Chart Line
                    chartPenyewa.data.datasets[0].data = data.chart_line;
                    chartPenyewa.update();

                    // Update Chart Bar (Baru)
                    // Dataset 0 = Total, Dataset 1 = Terpakai
                    chartBar.data.datasets[0].data = data.chart_bar_total;
                    chartBar.data.datasets[1].data = data.chart_bar_terpakai;
                    chartBar.update();

                    // Update Chart Doughnut (Jika Kost)
                    if (chartTipe) {
                        chartTipe.data.labels = data.chart_doughnut_labels;
                        chartTipe.data.datasets[0].data = data.chart_doughnut_data;
                        chartTipe.update();
                    }
                })
                .catch(error => console.error('Error fetching dashboard data:', error));
        }

        // Jalankan
        updateDashboard();
        setInterval(updateDashboard, 5000);
    });
</script>
@endpush
