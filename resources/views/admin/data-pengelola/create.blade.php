@extends('layout.admin')
@section('title', 'Tambah Data Pengelola')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    @if ($errors->any())
        <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <div class="flex items-center mb-1">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <strong class="font-bold">Terjadi Kesalahan!</strong>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-green-700 px-6 py-4 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-user"></i> Tambah Data
        </h3>
        <a href="{{ route('pengelola.index') }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm transition-colors border border-green-500">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Body Form --}}
    <form action="{{ route('pengelola.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap<span class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" placeholder="Masukkan nama lengkap..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('nama_lengkap') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- No Telp --}}
            <div>
                <label for="no_telp" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon<span class="text-red-500">*</span></label>
                <input type="number" id="no_telp" name="no_telp" value="{{ old('no_telp') }}"  placeholder="Masukkan no.telp..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('no_telp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email<span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"  placeholder="Masukkan email..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir<span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username<span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan username..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin<span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                    <option value="Laki-laki" {{ old('jenis_kelamin') }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin') }}>Perempuan</option>
                </select>
                @error('jenis_kelamin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password<span class="text-red-500">*</span></label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" placeholder="Masukkan password">
                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter.</p>
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Usia --}}
            <div>
                <label for="usia" class="block mb-2 text-sm font-medium text-gray-900">Usia<span class="text-red-500">*</span></label>
                <input type="number" id="usia" name="usia" value="{{ old('usia') }}" placeholder="Masukkan usia..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
            </div>

            {{-- Foto Profil --}}
            <div class="col-span-1 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Foto Profil</label>
                <p class="mt-1 text-xs text-gray-500" id="file_input_help">SVG, PNG, JPG (MAX. 2MB).</p>
                <div class="flex items-center gap-4">
                    {{-- <img src="{{ $pengelola->foto_profil ? asset('storage/' . $pengelola->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($pengelola->nama_lengkap) . '&background=10b981&color=fff' }}"
                        alt="Preview" class="w-16 h-16 rounded-full object-cover border border-gray-300"> --}}

                    <label for="file-input" class="sr-only">Pilih file</label>
                    <input type="file" name="file-input" id="file-input" class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4">
                    @error('foto_profil') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="block w-full h-px bg-gray-200 col-span-1 md:col-span-2 my-2"></div>
        </div>
        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-colors">
                Reset
            </button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection
