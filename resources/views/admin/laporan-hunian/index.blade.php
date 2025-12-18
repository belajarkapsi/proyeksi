@extends('layout.admin')
@section('title', 'Laporan Hunian')

@section('content')
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Hunian</h1>
            <p class="text-gray-500 mt-1 text-sm">Monitoring ketersediaan dan okupansi kamar.</p>
        </div>

        {{-- BUTTON PDF --}}
        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan-hunian.pdf', request()->query()) }}"
               class="inline-flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-sm transition-all text-sm font-medium focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Download PDF</span>
            </a>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-8">
        <div class="flex flex-col md:flex-row gap-4 items-end md:items-center">
            <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-end sm:items-center">
                
                {{-- Select Bulan --}}
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                    <div class="relative">
                        <select name="bulan" class="appearance-none w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            @for($i=1;$i<=12;$i++)
                                <option value="{{ $i }}" {{ $bulan==$i?'selected':'' }}>
                                    {{ date('F', mktime(0,0,0,$i,1)) }}
                                </option>
                            @endfor
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Select Tahun --}}
                <div class="w-full sm:w-auto">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                    <div class="relative">
                        <select name="tahun" class="appearance-none w-full bg-gray-50 border border-gray-300 text-gray-700 py-2 px-4 pr-8 rounded-lg leading-tight focus:outline-none focus:bg-white focus:border-blue-500">
                            @for($y=now()->year;$y>=2020;$y--)
                                <option value="{{ $y }}" {{ $tahun==$y?'selected':'' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>
                </div>

                {{-- Tombol Filter --}}
                <button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-sm transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-sm"></i> Filter
                </button>
            </form>
        </div>
    </div>

    {{-- RINGKASAN CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Total Kamar -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-gray-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Kamar</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalKamar }}</p>
            </div>
            <div class="p-3 bg-gray-100 rounded-full text-gray-600">
                <i class="fa-solid fa-door-closed text-xl"></i>
            </div>
        </div>

        <!-- Card 2: Dihuni -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sedang Dihuni</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $kamarDihuni }}</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                <i class="fa-solid fa-user-check text-xl"></i>
            </div>
        </div>

        <!-- Card 3: Tersedia -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Kamar Tersedia</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $kamarTersedia }}</p>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-600">
                <i class="fa-solid fa-key text-xl"></i>
            </div>
        </div>

        <!-- Card 4: Persentase -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Okupansi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $persentaseHunian }}%</p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-full text-indigo-600">
                <i class="fa-solid fa-chart-pie text-xl"></i>
            </div>
        </div>
    </div>

    {{-- CONTENT GRID (CHART & TABLE) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- GRAFIK SECTION --}}
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:col-span-1">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Rasio Hunian</h3>
            <div class="relative w-full h-64 flex justify-center">
                <canvas id="chartHunian"></canvas>
            </div>
            <div class="mt-4 flex justify-center gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-blue-500"></span> Dihuni
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-green-500"></span> Kosong
                </div>
            </div>
        </div>

        {{-- TABEL SECTION --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full lg:col-span-2">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Detail Per Cabang</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">Cabang</th>
                            <th scope="col" class="px-6 py-3 text-center">Total Kamar</th>
                            <th scope="col" class="px-6 py-3 text-center">Dihuni</th>
                            <th scope="col" class="px-6 py-3 text-right">Hunian (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($hunianPerCabang as $row)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $row->nama_cabang }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">
                                    {{ $row->total_kamar }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-200">
                                    {{ $row->dihuni }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-700">
                                {{ round(($row->dihuni / $row->total_kamar) * 100, 2) }}%
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                    <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ round(($row->dihuni / $row->total_kamar) * 100, 2) }}%"></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($hunianPerCabang->isEmpty())
                <div class="p-6 text-center text-gray-400">
                    Tidak ada data cabang ditemukan.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<script>
new Chart(document.getElementById('chartHunian'), {
    type: 'pie', // Logika chart tetap pie
    data: {
        labels: ['Dihuni', 'Kosong'],
        datasets: [{
            data: [{{ $kamarDihuni }}, {{ $kamarTersedia }}],
            backgroundColor: [
                '#3B82F6', // Blue-500 (Dihuni)
                '#10B981'  // Green-500 (Tersedia)
            ],
            borderColor: '#ffffff',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            display: false // Kita buat custom legend di HTML biar lebih rapi
        },
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var meta = dataset._meta[Object.keys(dataset._meta)[0]];
                    var total = meta.total;
                    var currentValue = dataset.data[tooltipItem.index];
                    var percentage = parseFloat((currentValue/total*100).toFixed(1));
                    return currentValue + ' (' + percentage + '%)';
                },
                title: function(tooltipItem, data) {
                    return data.labels[tooltipItem[0].index];
                }
            }
        }
    }
});
</script>
@endpush