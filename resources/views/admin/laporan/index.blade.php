@extends('layout.admin')
@section('title', 'Laporan Terpadu SIPESTAR')

@section('content')
<div class="container mx-auto px-4 pb-12">
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Dashboard</h1>
            <p class="text-gray-500 mt-1 text-sm">Analisis Hunian dan Keuangan real-time per cabang.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            {{-- Tombol PDF Dinamis --}}
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}" 
               id="btn-cetak-pdf"
               target="_blank"
               class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow-lg transition-all transform hover:-translate-y-0.5 text-sm font-bold">
                <i class="fa-solid fa-file-pdf"></i> Cetak Laporan PDF
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-8">
        <form id="filterForm" class="flex flex-wrap gap-4 items-end">
            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Pilih Cabang</label>
                <select name="cabang_id" id="filterCabang" onchange="updateData()" class="w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">Semua Cabang</option>
                    @foreach($cabangs as $c)
                        <option value="{{ $c->id_cabang }}" {{ request('cabang_id') == $c->id_cabang ? 'selected' : '' }}>
                            {{ $c->nama_cabang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Bulan</label>
                <select name="bulan" id="filterBulan" onchange="updateData()" class="w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$i,1)) }}</option>
                    @endfor
                </select>
            </div>

            <div class="w-full sm:w-auto">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase">Tahun</label>
                <select name="tahun" id="filterTahun" onchange="updateData()" class="w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    @for($y=now()->year; $y>=2022; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
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

    {{-- CHART & VILLA SERVICE ROW --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Left: Income Chart --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-line text-blue-500"></i> Tren Pendapatan 
                <span id="loading-line" class="hidden"><i class="fa-solid fa-spinner fa-spin text-xs"></i></span>
            </h3>
            <div class="h-72">
                <canvas id="chartKeuangan"></canvas>
            </div>
        </div>

        {{-- Right: Occupancy or Villa Services --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div id="wrapper-hunian">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2 text-scannable">
                    <i class="fa-solid fa-chart-pie text-indigo-500"></i> Komposisi Hunian
                </h3>
                <div class="h-52 flex justify-center">
                    <canvas id="chartHunian"></canvas>
                </div>
            </div>

            {{-- Villa Service Table (Real-time) --}}
            <div id="wrapper-layanan" class="hidden mt-6 border-t pt-4">
                <h3 class="font-bold text-gray-800 mb-3 text-xs uppercase flex items-center gap-2">
                    <i class="fa-solid fa-concierge-bell text-orange-500"></i> Layanan Villa Real-time
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-[11px] text-left">
                        <thead class="bg-gray-50 text-gray-500">
                            <tr>
                                <th class="px-2 py-1">Penyewa</th>
                                <th class="px-2 py-1">Layanan</th>
                                <th class="px-2 py-1 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="table-layanan-body" class="divide-y divide-gray-50">
                            {{-- Content loaded via JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- OKUPANSI PER CABANG TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-4 border-b bg-gray-50">
            <h3 class="font-bold text-gray-800">Status Hunian Per Cabang</h3>
        </div>
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr>
                    <th class="px-6 py-3">Nama Cabang</th>
                    <th class="px-6 py-3 text-center">Kapasitas</th>
                    <th class="px-6 py-3 text-center">Terisi</th>
                    <th class="px-6 py-3 text-right">Persentase</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($hunianPerCabang as $h)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-semibold text-gray-700">{{ $h->nama_cabang }}</td>
                    <td class="px-6 py-4 text-center">{{ $h->total_kamar }}</td>
                    <td class="px-6 py-4 text-center">{{ $h->dihuni }}</td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $h->total_kamar > 0 && ($h->dihuni/$h->total_kamar) > 0.8 ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $h->total_kamar > 0 ? round(($h->dihuni/$h->total_kamar)*100) : 0 }}%
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>
<script>
    let chartKeuangan, chartHunian;

    function formatRupiah(num) {
        return 'Rp ' + Number(num).toLocaleString('id-ID');
    }

    function initCharts() {
        const ctxK = document.getElementById('chartKeuangan').getContext('2d');
        chartKeuangan = new Chart(ctxK, {
            type: 'line',
            data: { labels: [], datasets: [{ label: 'Pendapatan', data: [], borderColor: '#3B82F6', backgroundColor: 'rgba(59, 130, 246, 0.1)', borderWidth: 3, lineTension: 0.3 }] },
            options: { responsive: true, maintainAspectRatio: false, legend: { display: false } }
        });

        const ctxH = document.getElementById('chartHunian').getContext('2d');
        chartHunian = new Chart(ctxH, {
            type: 'doughnut',
            data: { labels: ['Terisi', 'Kosong'], datasets: [{ data: [0, 0], backgroundColor: ['#6366F1', '#10B981'] }] },
            options: { responsive: true, maintainAspectRatio: false, cutoutPercentage: 70, legend: { position: 'bottom', labels: { fontSize: 10 } } }
        });
    }

    function updateData() {
        const loading = document.getElementById('loading-line');
        loading.classList.remove('hidden');

        const bulan = document.getElementById('filterBulan').value;
        const tahun = document.getElementById('filterTahun').value;
        const cabangId = document.getElementById('filterCabang').value;

        // Update PDF Link
        document.getElementById('btn-cetak-pdf').href = `{{ route('admin.laporan.pdf') }}?bulan=${bulan}&tahun=${tahun}&cabang_id=${cabangId}`;

        fetch(`{{ route('admin.laporan.api') }}?bulan=${bulan}&tahun=${tahun}&cabang_id=${cabangId}`)
            .then(res => res.json())
            .then(data => {
                // Update Charts
                chartKeuangan.data.labels = data.labels;
                chartKeuangan.data.datasets[0].data = data.values;
                chartKeuangan.update();

                chartHunian.data.datasets[0].data = [data.stats.kamarDihuni, data.stats.kamarTersedia];
                chartHunian.update();

                // Update Cards
                document.getElementById('stat-pendapatan').innerText = formatRupiah(data.stats.totalPendapatan);
                document.getElementById('stat-transaksi').innerText = data.stats.jumlahTransaksi;
                document.getElementById('stat-dihuni').innerText = data.stats.kamarDihuni;
                document.getElementById('stat-tersedia').innerText = data.stats.kamarTersedia;

                // Logika Villa / Real-time Service
                const wrapperLayanan = document.getElementById('wrapper-layanan');
                const tableBody = document.getElementById('table-layanan-body');

                if (data.isVilla || cabangId === "") {
                    wrapperLayanan.classList.remove('hidden');
                    tableBody.innerHTML = data.layanan.map(item => `
                        <tr>
                            <td class="px-2 py-2 font-medium">${item.penyewa}</td>
                            <td class="px-2 py-2 text-gray-500">${item.service}</td>
                            <td class="px-2 py-2 text-right font-bold text-blue-600">Rp${item.total}</td>
                        </tr>
                    `).join('');
                    if(data.layanan.length === 0) tableBody.innerHTML = '<tr><td colspan="3" class="text-center py-2 text-gray-400">Belum ada layanan</td></tr>';
                } else {
                    wrapperLayanan.classList.add('hidden');
                }

                loading.classList.add('hidden');
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initCharts();
        updateData();
        setInterval(updateData, 30000); // Auto-refresh setiap 30 detik
    });
</script>
@endpush