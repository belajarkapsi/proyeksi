@extends('layout.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-2">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
        </ol>
    </nav>
</div>

<div class="container px-6 mx-auto grid">

    {{-- Header Dashboard --}}
    <h2 class="py-4 text-2xl font-semibold text-gray-700">
        Dashboard
    </h2>

    {{-- KARTU STATISTIK (GRID 4 KOLOM) --}}
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

        {{-- Kartu 1: Primary --}}
        <div class="flex items-center p-4 bg-gray-100 rounded-lg shadow-xs border-l-4 border-blue-500">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">
                    Total Booking
                </p>
                <p class="text-lg font-semibold text-gray-700">
                    1,250
                </p>
            </div>
        </div>

        {{-- Kartu 2: Warning --}}
        <div class="flex items-center p-4 bg-gray-100 rounded-lg shadow-xs border-l-4 border-yellow-500">
            <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">
                    Kamar Terisi
                </p>
                <p class="text-lg font-semibold text-gray-700">
                    15
                </p>
            </div>
        </div>

        {{-- Kartu 3: Success --}}
        <div class="flex items-center p-4 bg-gray-100 rounded-lg shadow-xs border-l-4 border-green-500">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">
                    Pendapatan
                </p>
                <p class="text-lg font-semibold text-gray-700">
                    Rp 45.000.000
                </p>
            </div>
        </div>

        {{-- Kartu 4: Danger --}}
        <div class="flex items-center p-4 bg-gray-100 rounded-lg shadow-xs border-l-4 border-red-500">
            <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="mb-2 text-sm font-medium text-gray-600">
                    Pending
                </p>
                <p class="text-lg font-semibold text-gray-700">
                    5
                </p>
            </div>
        </div>
    </div>

    {{-- GRAFIK (GRID 2 KOLOM) --}}
    <div class="grid gap-6 mb-8 md:grid-cols-2">
        {{-- Area Chart --}}
        <div class="min-w-0 p-4 bg-gray-100 rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">
                Area Chart Example
            </h4>
            <canvas id="myAreaChart" class="w-full h-64"></canvas>
        </div>

        {{-- Bar Chart --}}
        <div class="min-w-0 p-4 bg-gray-100 rounded-lg shadow-xs">
            <h4 class="mb-4 font-semibold text-gray-800">
                Bar Chart Example
            </h4>
            <canvas id="myBarChart" class="w-full h-64"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load Chart.js via CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    // --- AREA CHART (TAILWIND STYLED) ---
    var ctx = document.getElementById("myAreaChart");
    if(ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["Mar 1", "Mar 2", "Mar 3", "Mar 4", "Mar 5", "Mar 6", "Mar 7", "Mar 8", "Mar 9", "Mar 10", "Mar 11", "Mar 12", "Mar 13"],
                datasets: [{
                    label: "Sessions",
                    lineTension: 0.3,
                    backgroundColor: "rgba(59, 130, 246, 0.2)", // Blue-500 with opacity
                    borderColor: "rgba(59, 130, 246, 1)", // Blue-500
                    pointRadius: 5,
                    pointBackgroundColor: "rgba(59, 130, 246, 1)",
                    pointBorderColor: "rgba(255,255,255,0.8)",
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(59, 130, 246, 1)",
                    pointHitRadius: 50,
                    pointBorderWidth: 2,
                    data: [10000, 30162, 26263, 18394, 18287, 28682, 31274, 33259, 25849, 24159, 32651, 31984, 38451],
                }],
            },
            options: {
                scales: {
                    xAxes: [{ time: { unit: 'date' }, gridLines: { display: false }, ticks: { maxTicksLimit: 7 } }],
                    yAxes: [{ ticks: { min: 0, max: 40000, maxTicksLimit: 5 }, gridLines: { color: "rgba(0, 0, 0, .125)" } }],
                },
                legend: { display: false }
            }
        });
    }

    // --- BAR CHART (TAILWIND STYLED) ---
    var ctx2 = document.getElementById("myBarChart");
    if(ctx2) {
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: ["January", "February", "March", "April", "May", "June"],
                datasets: [{
                    label: "Revenue",
                    backgroundColor: "rgba(16, 185, 129, 1)", // Green-500
                    borderColor: "rgba(16, 185, 129, 1)",
                    data: [4215, 5312, 6251, 7841, 9821, 14984],
                }],
            },
            options: {
                scales: {
                    xAxes: [{ time: { unit: 'month' }, gridLines: { display: false }, ticks: { maxTicksLimit: 6 } }],
                    yAxes: [{ ticks: { min: 0, max: 15000, maxTicksLimit: 5 }, gridLines: { display: true } }],
                },
                legend: { display: false }
            }
        });
    }
</script>
@endpush
