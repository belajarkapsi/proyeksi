@extends('layout.admin')
@section('title', 'Laporan Terpadu SIPESTAR')

@section('content')
<div class="container mx-auto px-4 pb-12">
    {{-- HEADER & DOWNLOAD ACTION --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Dashboard</h1>
            <p class="text-gray-500 mt-1 text-sm">Analisis Hunian dan Keuangan dalam satu tampilan terpadu.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            {{-- Tombol PDF --}}
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}" 
               target="_blank"
               class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-lg shadow-blue-100 transition-all transform hover:-translate-y-0.5 text-sm font-bold">
                <i class="fa-solid fa-file-pdf"></i> Cetak Laporan PDF
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-8">
        <form method="GET" id="filterForm" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Bulan</label>
                <select name="bulan" id="filterBulan" class="w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" {{ $bulan==$i?'selected':'' }}>
                            {{ date('F', mktime(0,0,0,$i,1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Tahun</label>
                <select name="tahun" id="filterTahun" class="w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @for($y=now()->year;$y>=2020;$y--)
                        <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-filter"></i> Terapkan Filter
            </button>
        </form>
    </div>

    {{-- STATISTIC CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-green-500">
            <p class="text-xs font-bold text-gray-400 uppercase">Total Pendapatan</p>
            <p class="text-xl font-bold text-gray-800 mt-1" id="stat-pendapatan">Rp {{ number_format($totalPendapatan) }}</p>
            <div class="mt-2 text-xs text-green-600 font-medium">
                <i class="fa-solid fa-receipt"></i> <span id="stat-transaksi">{{ $jumlahTransaksi }}</span> Transaksi
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-indigo-500">
            <p class="text-xs font-bold text-gray-400 uppercase">Rasio Hunian</p>
            <p class="text-xl font-bold text-gray-800 mt-1" id="stat-rasio">{{ $persentaseHunian }}%</p>
            <div class="mt-2 text-xs text-indigo-600 font-medium">
                <i class="fa-solid fa-door-closed"></i> <span id="stat-dihuni">{{ $kamarDihuni }}</span> / {{ $totalKamar }} Kamar
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-purple-500">
            <p class="text-xs font-bold text-gray-400 uppercase">Avg / Transaksi</p>
            <p class="text-xl font-bold text-gray-800 mt-1" id="stat-rata">Rp {{ number_format($rataRata) }}</p>
            <div class="mt-2 text-xs text-purple-600 font-medium">
                <i class="fa-solid fa-chart-line"></i> Nilai Rata-rata
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border-l-4 border-emerald-500">
            <p class="text-xs font-bold text-gray-400 uppercase">Kamar Kosong</p>
            <p class="text-xl font-bold text-gray-800 mt-1" id="stat-tersedia">{{ $kamarTersedia }} Unit</p>
            <div class="mt-2 text-xs text-emerald-600 font-medium">
                <i class="fa-solid fa-circle-check"></i> Siap Huni
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-blue-500"></i> Tren Pendapatan Harian
                <span id="loading-line" class="hidden"><i class="fa-solid fa-spinner fa-spin text-xs"></i></span>
            </h3>
            <div class="h-72">
                <canvas id="chartKeuangan"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-indigo-500"></i> Komposisi Hunian
            </h3>
            <div class="h-60 flex justify-center">
                <canvas id="chartHunian"></canvas>
            </div>
            <div class="mt-4 flex justify-center gap-4 text-xs font-semibold text-gray-500">
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-blue-500"></span> Dihuni</span>
                <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Kosong</span>
            </div>
        </div>
    </div>

    {{-- TABLES ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="font-bold text-gray-800">Okupansi Per Cabang</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3 text-center">Cabang</th>
                            <th class="px-4 py-3 text-center">Terisi</th>
                            <th class="px-4 py-3 text-right">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($hunianPerCabang as $row)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-700">{{ $row->nama_cabang }}</td>
                            <td class="px-4 py-3 text-center text-gray-600">{{ $row->dihuni }}/{{ $row->total_kamar }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <span class="font-bold text-blue-600 text-xs">{{ $row->total_kamar > 0 ? round(($row->dihuni / $row->total_kamar) * 100) : 0 }}%</span>
                                    <div class="w-16 bg-gray-100 rounded-full h-1.5">
                                        <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ $row->total_kamar > 0 ? ($row->dihuni/$row->total_kamar)*100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="font-bold text-gray-800">10 Transaksi Terakhir</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($transaksi as $t)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 italic text-gray-500 text-xs">
                                {{ \Carbon\Carbon::parse($t->waktu_pemesanan)->format('d/m H:i') }}
                            </td>
                            <td class="px-4 py-3 text-right font-bold text-gray-700">
                                Rp{{ number_format($t->total_harga) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ in_array(strtolower($t->status), ['lunas', 'paid']) ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ strtoupper($t->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
    let chartKeuangan, chartHunian;

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
    }

    function initCharts() {
        // Line Chart Initialization
        const ctxKeuangan = document.getElementById('chartKeuangan').getContext('2d');
        const grad = ctxKeuangan.createLinearGradient(0, 0, 0, 400);
        grad.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        grad.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        chartKeuangan = new Chart(ctxKeuangan, {
            type: 'line',
            data: { labels: [], datasets: [{ 
                label: 'Pendapatan', data: [], borderColor: '#3B82F6', backgroundColor: grad, borderWidth: 3, pointRadius: 3, lineTension: 0.3 
            }]},
            options: { 
                responsive: true, maintainAspectRatio: false, legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true, callback: (v) => v >= 1000000 ? (v/1000000)+'jt' : v.toLocaleString() }}] }
            }
        });

        // Doughnut Chart Initialization
        chartHunian = new Chart(document.getElementById('chartHunian'), {
            type: 'doughnut',
            data: {
                labels: ['Dihuni', 'Kosong'],
                datasets: [{ data: [0, 0], backgroundColor: ['#3B82F6', '#10B981'], borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutoutPercentage: 75, legend: { display: false } }
        });
    }

    function updateData() {
        const loadingLine = document.getElementById('loading-line');
        loadingLine.classList.remove('hidden');

        const bulan = document.getElementById('filterBulan').value;
        const tahun = document.getElementById('filterTahun').value;

        // AJAX Fetching
        fetch(`{{ route('admin.laporan.api') }}?bulan=${bulan}&tahun=${tahun}`)
            .then(res => res.json())
            .then(data => {
                // Update Line Chart
                chartKeuangan.data.labels = data.labels;
                chartKeuangan.data.datasets[0].data = data.values;
                chartKeuangan.update();

                // Update Doughnut Chart
                chartHunian.data.datasets[0].data = [data.stats.kamarDihuni, data.stats.kamarTersedia];
                chartHunian.update();

                // Update Real-time Statistic Cards (Jika ada perubahan data)
                // Catatan: Jika ingin update teks angka juga, tambahkan data tsb di JSON API
                
                loadingLine.classList.add('hidden');
            })
            .catch(err => console.error('Error fetching data:', err));
    }

    // Run on Load
    document.addEventListener('DOMContentLoaded', () => {
        initCharts();
        updateData();
        
        // Auto Update Every 30 Seconds
        setInterval(updateData, 30000);
    });
</script>
@endpush