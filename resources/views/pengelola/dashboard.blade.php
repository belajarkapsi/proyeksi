{{-- resources/views/pengelola/dashboard.blade.php --}}
@extends('layout.pengelola')
@section('title', 'Dashboard Pengelola')

@section('header')
<div class="py-4 px-4 sm:px-6 lg:px-8">
            <h1 class="max-w-7xl mx-autotext-2xl font-bold text-white">
                Selamat Datang, {{ Auth::user()->nama_lengkap ?? 'John Doe' }}
            </h1>
        </div>
@endsection

@section('content')
    <!-- Grid untuk Kartu Statistik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Kartu Total Kamar -->
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4">
            <div class="text-gray-400">
                <i class="fas fa-bed fa-3x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Kamar</p>
                <p class="text-3xl font-bold text-gray-800">30</p>
            </div>
        </div>

        <!-- Kartu Total Penyewa -->
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4">
            <div class="text-gray-400">
                <i class="fas fa-users fa-3x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Total Penyewa</p>
                <p class="text-3xl font-bold text-gray-800">10</p>
            </div>
        </div>

        <!-- Kartu Daftar Pesanan -->
        <div class="bg-white rounded-lg shadow-md p-6 flex items-center space-x-4">
            <div class="text-gray-400">
                <i class="fas fa-shopping-cart fa-3x"></i>
            </div>
            <div>
                <p class="text-sm text-gray-600 font-medium">Daftar Pesanan</p>
                <p class="text-3xl font-bold text-gray-800">5</p>
            </div>
        </div>
    </div>

    <!-- Grid untuk Grafik -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Grafik Peningkatan Penyewa -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                <h3 class="font-semibold text-green-700">Grafik Peningkatan Penyewa per Bulan</h3>
            </div>
            <div class="p-6 h-64 flex items-center justify-center">
                <p class="text-gray-400">[Placeholder untuk Bar Chart]</p>
            </div>
        </div>

        <!-- Grafik Penyebaran Jenis Kamar -->
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4 border-b bg-gray-50 rounded-t-lg">
                <h3 class="font-semibold text-green-700">Grafik Penyebaran Jenis Kamar</h3>
            </div>
            <div class="p-6 h-64 flex items-center justify-center">
                <p class="text-gray-400">[Placeholder untuk Pie Chart]</p>
            </div>
        </div>
    </div>
@endsection
