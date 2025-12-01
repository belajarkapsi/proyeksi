@extends('layout.admin')
@section('title', 'Edit Data Pengelola')

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
                <a href="{{ route('pengelola.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">Data Pengelola</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Edit Data</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-10">
    <div class="bg-green-700 px-6 py-4 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i> Edit Data Pengelola
        </h3>
        <a href="{{ route('pengelola.index') }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm transition-colors border border-green-500">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Start --}}
    {{-- PENTING: enctype="multipart/form-data" wajib ada untuk upload foto --}}
    {{-- Route menggunakan parameter $pengelola->id_penyewa karena primary key custom --}}
    <form action="{{ route('pengelola.update', $pengelola->id_pengelola) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama Lengkap --}}
            <div class="col-span-1 md:col-span-2">
                <label for="nama_lengkap" class="block mb-2 text-sm font-medium text-gray-900">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $pengelola->nama_lengkap) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('nama_lengkap') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Username --}}
            <div>
                <label for="username" class="block mb-2 text-sm font-medium text-gray-900">Username <span class="text-red-500">*</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', $pengelola->username) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email', $pengelola->email) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- No Telp --}}
            <div>
                <label for="no_telp" class="block mb-2 text-sm font-medium text-gray-900">Nomor Telepon <span class="text-red-500">*</span></label>
                <input type="number" id="no_telp" name="no_telp" value="{{ old('no_telp', $pengelola->no_telp) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('no_telp') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block mb-2 text-sm font-medium text-gray-900">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select id="jenis_kelamin" name="jenis_kelamin" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                    <option value="Laki-laki" {{ old('jenis_kelamin', $pengelola->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $pengelola->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label for="tanggal_lahir" class="block mb-2 text-sm font-medium text-gray-900">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pengelola->tanggal_lahir) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
            </div>

            {{-- Usia --}}
            <div>
                <label for="usia" class="block mb-2 text-sm font-medium text-gray-900">Usia</label>
                <input type="number" id="usia" name="usia" value="{{ old('usia', $pengelola->usia) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
            </div>

            <div class="block w-full h-px bg-gray-200 col-span-1 md:col-span-2 my-2"></div>

            {{-- Foto Profil --}}
            <div class="col-span-1 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Foto Profil</label>
                <div class="flex items-center gap-4">
                    <img src="{{ $pengelola->foto_profil ? asset('storage/' . $pengelola->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($pengelola->nama_lengkap) . '&background=10b981&color=fff' }}"
                        alt="Preview" class="w-16 h-16 rounded-full object-cover border border-gray-300">

                    <div class="w-full">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="foto_profil" name="foto_profil" type="file" accept="image/*">
                        <p class="mt-1 text-xs text-gray-500" id="file_input_help">SVG, PNG, JPG (MAX. 2MB).</p>
                        @error('foto_profil') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Password --}}
            <div class="col-span-1 md:col-span-2">
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password Baru</label>
                <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" placeholder="Kosongkan jika tidak ingin mengganti password">
                <p class="mt-1 text-xs text-gray-500">Minimal 6 karakter.</p>
                @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="block w-full h-px bg-gray-200 col-span-1 md:col-span-2 my-2"></div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-3 mt-8">
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
