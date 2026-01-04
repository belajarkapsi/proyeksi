@extends('layout.admin')
@section('title', 'Admin Dashboard SIPESTAR')

@section('content')
<div class="w-full px-4 py-4 mx-auto">

    {{-- HEADER --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dashboard Admin</h2>
            <p class="text-gray-500 mt-1">Status Properti Saat Ini: <span class="font-bold text-blue-600">{{ $persentaseHunian }}% Terisi</span></p>
        </div>
        <div id="sync-indicator" class="hidden flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold animate-pulse border border-blue-100">
            <i class="fa-solid fa-sync fa-spin"></i> MENYINKRONKAN DATA...
        </div>
    </div>

    {{-- 7 STAT CARDS (Real-time Updated) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <x-stat-card id="totalBooking" label="Total Booking" value="{{ $totalBooking }}" color="blue" icon="fa-solid fa-calendar-check" />
        <x-stat-card id="totalKamar" label="Total Kamar" value="{{ $totalKamar }}" color="orange" icon="fa-solid fa-hotel" />
        <x-stat-card id="kamarTerisi" label="Kamar Terisi" value="{{ $kamarDihuni }}" color="indigo" icon="fa-solid fa-door-closed" />
        <x-stat-card id="kamarTersedia" label="Kamar Tersedia" value="{{ $kamarTersedia }}" color="emerald" icon="fa-solid fa-circle-check" />
        <x-stat-card id="totalCabang" label="Total Cabang" value="{{ $totalCabang }}" color="purple" icon="fa-solid fa-city" />
        <x-stat-card id="totalLayanan" label="Total Layanan" value="{{ $totalLayanan }}" color="pink" icon="fa-solid fa-concierge-bell" />
        <x-stat-card id="totalPenyewa" label="Total Penyewa" value="{{ $totalPenyewa }}" color="green" icon="fa-solid fa-users" />
    </div>

    {{-- ROW 1: CHARTS --}}
    <div class="grid gap-6 mb-8 grid-cols-1 lg:grid-cols-3">
        {{-- TREN PEMESANAN (LINE CHART) --}}
        <div class="lg:col-span-2 p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-blue-500"></i> Tren Pemesanan Mingguan
            </h4>
            <div class="relative h-64 w-full"><canvas id="bookingChart"></canvas></div>
        </div>

        {{-- STATUS KAMAR GLOBAL (DOUGHNUT) --}}
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-indigo-500"></i> Rasio Kamar Global
            </h4>
            <div class="relative h-64 w-full"><canvas id="kamarDoughnutChart"></canvas></div>
        </div>
    </div>

    {{-- ROW 2: TABLES (CABANG & LAYANAN) --}}
    <div class="grid gap-6 mb-8 grid-cols-1 lg:grid-cols-2">
        {{-- TABEL OKUPANSI PER CABANG --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-building-circle-check text-purple-500"></i> Okupansi Per Cabang
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">Nama Cabang</th>
                            <th class="px-4 py-3 text-center">Hunian (Terisi/Total)</th>
                            <th class="px-4 py-3 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="cabang-table-body">
                        @foreach($hunianPerCabang as $row)
                        @php $persen = $row->total_kamar > 0 ? round(($row->dihuni / $row->total_kamar) * 100) : 0; @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $row->nama_cabang }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-mono text-xs">
                                    {{ $row->dihuni }} / {{ $row->total_kamar }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <span class="font-bold {{ $persen > 80 ? 'text-red-600' : 'text-blue-600' }} text-xs">{{ $persen }}%</span>
                                    <div class="w-24 bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full {{ $persen > 80 ? 'bg-red-500' : 'bg-blue-500' }}" style="width: {{ $persen }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TABEL RINGKASAN LAYANAN (PENGGANTI CHART) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-concierge-bell text-pink-500"></i> Ringkasan Layanan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">Nama Layanan</th>
                            <th class="px-4 py-3 text-right">Total Dipesan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="layanan-table-body">
                        @foreach($dataLayanan as $layanan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $layanan->name }}</td>
                            <td class="px-4 py-3 text-right font-bold text-pink-600">
                                {{ $layanan->pemesanan_service_count }} <span class="text-[10px] text-gray-400 font-normal">Kali</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ROW 3: PENYEWA TERBARU --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-user-clock text-green-500"></i> 
                {{ count($penyewaTerbaru) }} Penyewa Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase border-b bg-gray-50">
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Username</th>
                        <th class="py-3 px-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody id="penyewa-table-body">
                    @foreach($penyewaTerbaru as $p)
                    <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-medium text-gray-700">{{ $p->nama_lengkap }}</td>
                        <td class="py-3 px-4 text-gray-500">@<span>{{ $p->username }}</span></td>
                        <td class="py-3 px-4 text-right">
                            <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-green-100 text-green-600 uppercase">Aktif</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
    let charts = {};

    function initCharts() {
        // Line Chart - Tren Booking
        charts.booking = new Chart(document.getElementById('bookingChart'), {
            type: 'line',
            data: { 
                labels: @json($areaChart->pluck('tanggal')), 
                datasets: [{ 
                    label: 'Booking', 
                    data: @json($areaChart->pluck('total')), 
                    borderColor: '#3B82F6', 
                    backgroundColor: 'rgba(59, 130, 246, 0.1)', 
                    fill: true, 
                    lineTension: 0.3,
                    pointBackgroundColor: '#3B82F6'
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                legend: { display: false },
                scales: {
                    yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
                }
            }
        });

        // Doughnut Chart - Rasio Kamar
        charts.kamar = new Chart(document.getElementById('kamarDoughnutChart'), {
            type: 'doughnut',
            data: { 
                labels: ['Dihuni', 'Tersedia'], 
                datasets: [{ 
                    data: [{{ $kamarDihuni }}, {{ $kamarTersedia }}], 
                    backgroundColor: ['#6366F1', '#10B981'],
                    borderWidth: 0
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false, 
                cutoutPercentage: 75,
                legend: { position: 'bottom', labels: { boxWidth: 12 } }
            }
        });
    }

    function refreshData() {
        document.getElementById('sync-indicator').classList.remove('hidden');

        fetch('{{ route("admin.dashboard.api") }}')
            .then(res => res.json())
            .then(res => {
                const s = res.stats;
                
                // 1. Update Stat Cards (Real-time)
                document.getElementById('val-totalBooking').innerText = s.totalBooking;
                document.getElementById('val-totalKamar').innerText = s.totalKamar;
                document.getElementById('val-kamarTerisi').innerText = s.kamarDihuni;
                document.getElementById('val-kamarTersedia').innerText = s.kamarTersedia;
                document.getElementById('val-totalCabang').innerText = s.totalCabang;
                document.getElementById('val-totalLayanan').innerText = s.totalLayanan;
                document.getElementById('val-totalPenyewa').innerText = s.totalPenyewa;

                // 2. Update Charts
                charts.booking.data.labels = res.area.labels;
                charts.booking.data.datasets[0].data = res.area.values;
                charts.kamar.data.datasets[0].data = [s.kamarDihuni, s.kamarTersedia];

                // 3. Update Tabel Cabang (Real-time Progress Bar)
                let cabangHtml = '';
                res.hunianPerCabang.forEach(row => {
                    const p = row.total_kamar > 0 ? Math.round((row.dihuni / row.total_kamar) * 100) : 0;
                    cabangHtml += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-700">${row.nama_cabang}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 bg-gray-100 rounded text-gray-600 font-mono text-xs">${row.dihuni} / ${row.total_kamar}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-3">
                                    <span class="font-bold ${p > 80 ? 'text-red-600' : 'text-blue-600'} text-xs">${p}%</span>
                                    <div class="w-24 bg-gray-100 rounded-full h-1.5">
                                        <div class="h-1.5 rounded-full ${p > 80 ? 'bg-red-500' : 'bg-blue-500'}" style="width: ${p}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>`;
                });
                document.getElementById('cabang-table-body').innerHTML = cabangHtml;

                // 4. Update Tabel Layanan (Real-time Data)
                let layananHtml = '';
                // 'res.layananRaw' harus dikirim dari controller (data mentah layanan)
                res.layananRaw.forEach(layanan => {
                    layananHtml += `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-medium text-gray-700">${layanan.name}</td>
                            <td class="px-4 py-3 text-right font-bold text-pink-600">
                                ${layanan.pemesanan_service_count} <span class="text-[10px] text-gray-400 font-normal">Kali</span>
                            </td>
                        </tr>`;
                });
                document.getElementById('layanan-table-body').innerHTML = layananHtml;

                // 5. Update Tabel Penyewa
                let penyewaHtml = '';
                res.penyewaTerbaru.forEach(p => {
                    penyewaHtml += `
                        <tr class="border-b border-gray-50 hover:bg-gray-50">
                            <td class="py-3 px-4 font-medium text-gray-700">${p.nama_lengkap}</td>
                            <td class="py-3 px-4 text-gray-500">@${p.username}</td>
                            <td class="py-3 px-4 text-right">
                                <span class="px-2 py-1 text-[10px] font-bold rounded-full bg-green-100 text-green-600 uppercase">Aktif</span>
                            </td>
                        </tr>`;
                });
                document.getElementById('penyewa-table-body').innerHTML = penyewaHtml;

                // Refresh Visual Charts
                Object.values(charts).forEach(c => c.update());
                
                // Hide Sync Indicator
                setTimeout(() => document.getElementById('sync-indicator').classList.add('hidden'), 1000);
            })
            .catch(err => console.error("Refresh Error:", err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        initCharts();
        setInterval(refreshData, 15000); // Sinkronisasi otomatis tiap 15 detik
    });
</script>
@endpush