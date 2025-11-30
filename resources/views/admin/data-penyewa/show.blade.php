@extends('layout.admin')
@section('title', 'Detail Penyewa')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8 mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <a href="{{ route('penyewa.index') }}" class="text-gray-400 hover:text-green-600 text-sm font-medium transition-colors">Data Penyewa</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">{{ $user->username }}</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    {{-- Header Card --}}
    <div class="bg-green-700 px-6 py-4 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-user"></i> Rincian Biodata
        </h3>
        <a href="{{ route('penyewa.index') }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Body Card --}}
    <div class="p-6 md:p-8">
        <div class="flex flex-col md:flex-row gap-8">

            {{-- Foto Profil (Kiri) --}}
            <div class="shrink-0 flex flex-col items-center">
                <img src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama_lengkap) . '&background=10b981&color=fff&size=128' }}"
                    alt="{{ Auth::user()->nama_lengkap ?? 'User' }}"
                    class="w-32 h-32 md:w-40 md:h-40 rounded-full object-cover border-4 border-green-100 shadow-md mb-4">

                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $user->jenis_kelamin == 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                    {{ $user->jenis_kelamin }}
                </span>
            </div>

            {{-- Informasi Detail (Kanan) --}}
            <div class="grow grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">

                <div class="col-span-1 md:col-span-2">
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Nama Lengkap</label>
                    <p class="text-lg font-medium text-gray-800 border-b border-gray-100 pb-1">{{ $user->nama_lengkap }}</p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Username:</label>
                    <p class="text-md text-gray-800">{{ $user->username }}</p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Email:</label>
                    <p class="text-md text-gray-800">{{ $user->email }}</p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Nomor Telepon:</label>
                    <p class="text-md text-gray-800">{{ $user->no_telp }}</p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Tanggal Lahir:</label>
                    <p class="text-md text-gray-800">
                        {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}
                        <span class="text-sm text-gray-400">({{ $user->usia }} Tahun)</span>
                    </p>
                </div>

                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Asal / Kota:</label>
                    <p class="text-md text-gray-800">{{ $user->asal }}</p>
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Alamat Lengkap:</label>
                    <p class="text-md text-gray-800 bg-gray-50 p-3 rounded-md border border-gray-100 mt-1">
                        {{ $user->alamat }}
                    </p>
                </div>

                <div class="col-span-1 md:col-span-2 mt-2">
                    <label class="text-xs text-gray-500 uppercase tracking-wide font-bold">Bergabung Sejak:</label>
                    <p class="text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('l, d F Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
