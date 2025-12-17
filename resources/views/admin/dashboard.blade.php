@extends('layout.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="w-full px-2 py-2 mx-auto">

    {{-- HEADER --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
            <p class="text-gray-500 mt-1">Ringkasan statistik dan performa sistem.</p>
        </div>
    </div>

    {{-- GRID KARTU STATISTIK --}}
    <div class="grid gap-6 mb-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

        {{-- TOTAL BOOKING --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
            <div class="p-3 mr-4 text-blue-600 bg-blue-100 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                <!-- Icon Calendar -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Total Booking</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalBooking }}</p>
            </div>
        </div>

        {{-- TOTAL KAMAR --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-lg group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300">
                <!-- Icon Home -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Total Kamar</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalKamar }}</p>
            </div>
        </div>

        {{-- KAMAR TERISI --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
            <div class="p-3 mr-4 text-emerald-500 bg-emerald-100 rounded-lg group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                <!-- Icon Key/Check -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Kamar Terisi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $kamarTerisi }}</p>
            </div>
        </div>

        {{-- KAMAR TERSEDIA --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
            <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-lg group-hover:bg-red-500 group-hover:text-white transition-colors duration-300">
                <!-- Icon Door Open -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Kamar Tersedia</p>
                <p class="text-2xl font-bold text-gray-800">{{ $kamarTersedia }}</p>
            </div>
        </div>

        {{-- TOTAL CABANG --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
            <div class="p-3 mr-4 text-indigo-500 bg-indigo-100 rounded-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300">
                <!-- Icon Building -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Total Cabang</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalCabang }}</p>
            </div>
        </div>

        {{-- TOTAL LAYANAN --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100">
            <div class="p-3 mr-4 text-purple-500 bg-purple-100 rounded-lg group-hover:bg-purple-500 group-hover:text-white transition-colors duration-300">
                <!-- Icon Cube/Services -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Total Layanan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalLayanan }}</p>
            </div>
        </div>

        {{-- TOTAL AKUN PENYEWA --}}
        <div class="group flex items-center p-5 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 border border-gray-100 sm:col-span-2 lg:col-span-1">
            <div class="p-3 mr-4 text-pink-500 bg-pink-100 rounded-lg group-hover:bg-pink-500 group-hover:text-white transition-colors duration-300">
                <!-- Icon Users -->
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Total Penyewa</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalPenyewa }}</p>
            </div>
        </div>

    </div>

    {{-- ===================== --}}
    {{-- CHART SECTION --}}
    {{-- ===================== --}}
    <div class="grid gap-6 mb-8 grid-cols-1 lg:grid-cols-2">

        {{-- CHART 1 : BOOKING / PEMESANAN --}}
        <div class="min-w-0 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-800">Tren Pemesanan</h4>
                <span class="text-xs font-medium text-blue-600 bg-blue-100 py-1 px-2 rounded-full">Realtime</span>
            </div>
            {{-- Wrapper untuk responsivitas Chart.js --}}
            <div class="relative h-64 w-full">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>

        {{-- CHART 2 : RINGKASAN DATA --}}
        <div class="min-w-0 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-lg font-semibold text-gray-800">Ringkasan Sistem</h4>
                <span class="text-xs font-medium text-gray-500">Status Saat Ini</span>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="summaryChart"></canvas>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Load Chart.js 2.8 --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<script>
/* ===============================
   DATA DARI BACKEND
================================ */
const bookingLabels = @json($areaChart->pluck('tanggal'));
const bookingData   = @json($areaChart->pluck('total'));

const summaryLabels = [
    'Booking', 'Kamar', 'Terisi', 'Tersedia', 'Cabang', 'Layanan', 'Penyewa'
];

const summaryData = [
    {{ $totalBooking }},
    {{ $totalKamar }},
    {{ $kamarTerisi }},
    {{ $kamarTersedia }},
    {{ $totalCabang }},
    {{ $totalLayanan }},
    {{ $totalPenyewa }}
];

/* ===============================
   KONFIGURASI UMUM (STYLING)
================================ */
// Font family default agar sesuai dengan UI
Chart.defaults.global.defaultFontFamily = "'Inter', 'Segoe UI', 'Roboto', 'Helvetica', 'Arial', sans-serif";
Chart.defaults.global.defaultFontColor = '#6B7280'; // gray-500

/* ===============================
   CHART 1 : BOOKING (Line Chart)
================================ */
const ctxBooking = document.getElementById('bookingChart').getContext('2d');

// Membuat Gradient fill untuk area chart
const gradientBooking = ctxBooking.createLinearGradient(0, 0, 0, 400);
gradientBooking.addColorStop(0, 'rgba(59, 130, 246, 0.5)'); // Blue start
gradientBooking.addColorStop(1, 'rgba(59, 130, 246, 0.05)'); // Blue end

const bookingChart = new Chart(ctxBooking, {
    type: 'line',
    data: {
        labels: bookingLabels,
        datasets: [{
            label: 'Booking',
            data: bookingData,
            backgroundColor: gradientBooking,
            borderColor: '#3B82F6', // Blue-500
            borderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#3B82F6',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6,
            lineTension: 0.4, // Membuat garis melengkung halus
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        tooltips: {
            mode: 'index',
            intersect: false,
            backgroundColor: 'rgba(255, 255, 255, 0.9)',
            titleFontColor: '#1F2937',
            bodyFontColor: '#4B5563',
            borderColor: '#E5E7EB',
            borderWidth: 1,
            xPadding: 10,
            yPadding: 10,
            displayColors: false,
            cornerRadius: 4
        },
        scales: {
            xAxes: [{
                gridLines: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    maxTicksLimit: 5,
                    padding: 10
                },
                gridLines: {
                    color: '#F3F4F6', // gray-100
                    borderDash: [5, 5],
                    drawBorder: false,
                    zeroLineColor: '#F3F4F6'
                }
            }]
        }
    }
});

/* ===============================
   CHART 2 : RINGKASAN (Bar Chart)
================================ */
const summaryChart = new Chart(
    document.getElementById('summaryChart'),
    {
        type: 'bar',
        data: {
            labels: summaryLabels,
            datasets: [{
                label: 'Jumlah',
                data: summaryData,
                backgroundColor: [
                    '#3B82F6', // Blue
                    '#F97316', // Orange
                    '#10B981', // Emerald
                    '#EF4444', // Red
                    '#6366F1', // Indigo
                    '#A855F7', // Purple
                    '#EC4899'  // Pink
                ],
                borderWidth: 0,
                barPercentage: 0.6,
                categoryPercentage: 0.8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            tooltips: {
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                titleFontColor: '#1F2937',
                bodyFontColor: '#4B5563',
                borderColor: '#E5E7EB',
                borderWidth: 1,
                xPadding: 10,
                yPadding: 10,
                cornerRadius: 4,
                displayColors: true
            },
            scales: {
                xAxes: [{
                    gridLines: { display: false, drawBorder: false },
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 5,
                        padding: 10
                    },
                    gridLines: {
                        color: '#F3F4F6',
                        borderDash: [5, 5],
                        drawBorder: false,
                        zeroLineColor: '#F3F4F6'
                    }
                }]
            }
        }
    }
);

/* ===============================
   REALTIME POLLING
================================ */
setInterval(() => {
    fetch('/api/admin/chart')
        .then(res => res.json())
        .then(res => {
            // Update Line Chart Data
            bookingChart.data.labels = res.area.map(i => i.tanggal);
            bookingChart.data.datasets[0].data = res.area.map(i => i.total);
            bookingChart.update();
        });
}, 5000);
</script>
@endpush