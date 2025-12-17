{{-- resources/views/pengelola/dashboard.blade.php --}}
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

        <div class="bg-white rounded-xl shadow">
            <div class="p-4 border-b bg-gray-50 rounded-t-xl">
                <h3 class="font-semibold text-gray-700">
                    Tren Penyewa Bulanan
                </h3>
            </div>
            <div class="p-6 h-64 flex items-center justify-center text-gray-400">
                Grafik akan ditampilkan di sini
            </div>
        </div>

        <div class="bg-white rounded-xl shadow">
            <div class="p-4 border-b bg-gray-50 rounded-t-xl">
                <h3 class="font-semibold text-gray-700">
                    Distribusi Tipe Kamar
                </h3>
            </div>

            <div class="p-6 h-64 flex items-center justify-center text-gray-400">
                Grafik akan ditampilkan di sini
            </div>
        </div>
    </div>
@endsection
