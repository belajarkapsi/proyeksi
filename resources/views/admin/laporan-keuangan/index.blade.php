@extends('layout.admin')
@section('title', 'Laporan Keuangan')

@section('content')
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan</h1>
            <p class="text-gray-500 mt-1 text-sm">Ringkasan pendapatan dan aktivitas transaksi bulan ini.</p>
        </div>
        
        {{-- BUTTON PDF --}}
        <div class="mt-4 md:mt-0">
            <a href="{{ route('laporan-keuangan.pdf', request()->query()) }}"
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

                <button class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-sm transition-colors font-medium flex items-center justify-center gap-2">
                    <i class="fa-solid fa-filter text-sm"></i> Filter
                </button>
            </form>
        </div>
    </div>

    {{-- RINGKASAN CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Card 1: Total Pendapatan -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalPendapatan) }}</p>
            </div>
            <div class="p-3 bg-green-50 rounded-full text-green-600">
                <i class="fa-solid fa-money-bill-wave text-xl"></i>
            </div>
        </div>

        <!-- Card 2: Jumlah Transaksi -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $jumlahTransaksi }} <span class="text-sm font-normal text-gray-400">Trx</span></p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                <i class="fa-solid fa-receipt text-xl"></i>
            </div>
        </div>

        <!-- Card 3: Rata-rata -->
        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 uppercase tracking-wider">Rata-rata / Transaksi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($rataRata) }}</p>
            </div>
            <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
        </div>
    </div>

    {{-- CONTENT GRID (CHART & TABLE) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- GRAFIK SECTION (Takes 2 columns on large screens) --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Grafik Pendapatan Harian</h3>
            <div class="relative w-full h-80">
                <canvas id="chartKeuangan"></canvas>
            </div>
        </div>

        {{-- TABEL SECTION (Takes 1 column on large screens, side-by-side) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full">
            <div class="p-4 border-b bg-gray-50">
                <h3 class="text-lg font-bold text-gray-800">Riwayat Transaksi</h3>
            </div>
            
            <div class="overflow-y-auto max-h-[400px]"> {{-- Scrollable table if too long --}}
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 sticky top-0">
                        <tr>
                            <th scope="col" class="px-6 py-3">Tanggal</th>
                            <th scope="col" class="px-6 py-3">ID Order</th>
                            <th scope="col" class="px-6 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($transaksi as $t)
                        <tr class="bg-white hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ $t->waktu_pemesanan->format('d/m/Y') }}
                                <br>
                                <span class="text-xs text-gray-400">{{ $t->waktu_pemesanan->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-200">
                                    #{{ $t->id_pemesanan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-700">
                                Rp {{ number_format($t->total_harga) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($transaksi->isEmpty())
                <div class="p-6 text-center text-gray-400">
                    Tidak ada data transaksi bulan ini.
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Pastikan FontAwesome sudah ada di layout utama, jika belum tambahkan CDN di sini --}}
{{-- <script src="https://kit.fontawesome.com/your-code.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

<script>
    // Sedikit styling tambahan pada Chart agar lebih enak dilihat
    var ctx = document.getElementById('chartKeuangan').getContext('2d');
    
    // Membuat Gradient Warna untuk Chart
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); // Blue with opacity
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($grafik->pluck('hari')) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($grafik->pluck('total')) !!},
                borderColor: '#2563EB', // Tailwind Blue-600
                backgroundColor: gradient,
                borderWidth: 2,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#2563EB',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false
            },
            tooltips: {
                mode: 'index',
                intersect: false,
                backgroundColor: 'rgba(0,0,0,0.8)',
                titleFontColor: '#fff',
                bodyFontColor: '#fff',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                callbacks: {
                    label: function(tooltipItem, data) {
                        return 'Rp ' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                }
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false
                    },
                    ticks: {
                        fontColor: '#9CA3AF'
                    }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        fontColor: '#9CA3AF',
                        callback: function(value, index, values) {
                            if(value >= 1000000) return 'Rp ' + value/1000000 + 'jt';
                            if(value >= 1000) return 'Rp ' + value/1000 + 'rb';
                            return value;
                        }
                    },
                    gridLines: {
                        color: '#F3F4F6',
                        borderDash: [5, 5]
                    }
                }]
            }
        }
    });
</script>
@endpush