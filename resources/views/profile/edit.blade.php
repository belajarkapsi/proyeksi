@extends('layout.master') 
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
    <!-- Biodata (Kiri) -->
        <div class="bg-gray-100 rounded-2xl p-6 h-fit shadow-sm">
            <div class="flex flex-col items-center mb-6">
                <div class="relative">
                    @if($penyewa->foto_profil)
                        <img class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md" 
                            src="{{ asset('storage/' . $penyewa->foto_profil) }}" 
                            alt="{{ $penyewa->nama_lengkap }}">
                    @else
                        <img class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md" 
                            src="https://ui-avatars.com/api/?name={{ urlencode($penyewa->nama_lengkap) }}&background=random&size=128" 
                            alt="Default Avatar">
                    @endif
                    
                    <label for="foto_profil_input" class="absolute bottom-0 right-0 bg-green-600 hover:bg-green-700 text-white p-2 rounded-full cursor-pointer shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                </div>
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $penyewa->nama_lengkap }}</h2>
                <p class="text-sm text-gray-500">Penyewa</p>
                <a href="#" class="text-xs text-green-600 font-medium mt-1 cursor-pointer hover:underline" onclick="document.getElementById('foto_profil_input').click();">Ubah Foto Profil</a>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-500 ">Email:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-300 pb-1">{{ $penyewa->email }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">No Telepon:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-300 pb-1">{{ $penyewa->no_telp ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Asal:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-300 pb-1">{{ $penyewa->asal ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Jenis Kelamin:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-300 pb-1">{{ $penyewa->jenis_kelamin ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Usia:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-300 pb-1">{{ $penyewa->usia ? $penyewa->usia . ' Tahun' : '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Form Edit (Kanan) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input type="file" name="foto_profil" id="foto_profil_input" class="hidden" accept="image/*" onchange="document.getElementById('form-submit-btn').classList.remove('hidden')">

                <div class="space-y-6">
                    <div name="nama_lengkap">
                        <label for="nama_lengkap" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap<span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $penyewa->nama_lengkap) }}" 
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="username">
                        <label for="username" class="block text-sm font-bold text-gray-700 mb-2">Username<span class="text-red-500">*</span></label>
                        <input type="username" name="username" id="username" value="{{ old('username', $penyewa->username) }}" 
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="email">
                        <label for="email" class="block text-sm font-bold text-gray-500 mb-2">Email (Tidak dapat diubah)</label>
                        <input type="email" value="{{ $penyewa->email }}" disabled
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">Hubungi admin jika ingin mengubah email.</p>
                    </div>

                    <div name="no_telepon">
                        <label for="no_telp" class="block text-sm font-bold text-gray-700 mb-2">No. Telepon<span class="text-red-500">*</span></label>
                        <input type="number" name="no_telp" id="no_telp" value="{{ old('no_telp', $penyewa->no_telp) }}" aria-describedby="helper-text-explanation"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" pattern="[0-9]{4}-[0-9]{4}-[0-9]{5}" required />
                        @error('no_telp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="asal">
                        <label for="asal" class="block text-sm font-bold text-gray-700 mb-2">Asal Kota/Daerah<span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="asal" id="asal" class="py-3 px-4 pe-9 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all" required>
                                <option value="" disabled {{ !$penyewa->kota_asal ? 'selected' : '' }}>Pilih Asal Kotamu</option>
                                @php
                                    $kotas = ['Jakarta', 'Makassar', 'Surabaya', 'Parepare', 'Pangkep', 'Sidrap', 'Pinrang', 'Barru', 'Maros'];
                                @endphp
                                @foreach ($kotas as $kota)
                                    <option value="{{ $kota }}" {{ old('asal', $penyewa->asal) == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('asal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="alamat">
                        <label for="alamat" class="block text-sm font-bold text-gray-700 mb-2">Alamat<span class="text-red-500">*</span></label>
                        <textarea name="alamat" id="alamat" rows="2"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all" required>{{ old('alamat', $penyewa->alamat) }}</textarea>
                        @error('alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div name="jenis_kelamin">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin<span class="text-red-500">*</span></label>
                            <div class="flex flex-col space-y-2 mt-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" id="gender_male" 
                                        class="shrink-0 mt-0.5 border-gray-300 rounded-full text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                                        {{ old('jenis_kelamin', $penyewa->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }} required>
                                    <label for="gender_male" class="text-sm text-gray-500 ms-2">Laki-laki</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" id="gender_female" 
                                        class="shrink-0 mt-0.5 border-gray-200 rounded-full text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                                        {{ old('jenis_kelamin', $penyewa->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}>
                                    <label for="gender_female" class="text-sm text-gray-500 ms-2">Perempuan</label>
                                </div>
                            </div>
                            @error('jenis_kelamin') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="bg-purple-50/50 p-4 rounded-xl border border-purple-100" name="tanggal_lahir"> 
                            <label for="tanggal_lahir" class="block text-sm font-bold text-gray-800 mb-2">Tanggal Lahir<span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $penyewa->tanggal_lahir) }}" 
                                    class="py-3 px-4 block w-full bg-gray-100 border-green-500 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white" required>
                                <div class="absolute inset-y-0 end-0 flex items-center pointer-events-none pe-3">
                                    <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Usia akan dihitung otomatis.</p>
                                @error('tanggal_lahir') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" id="form-submit-btn" class="py-3 px-8 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none shadow-md transition-all hover:shadow-lg">
                            Simpan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection