@extends('layout.pengelola')

@section('title', 'Edit Penyewa: ' . $user->nama_lengkap)

@section('content')
<div class="container mx-auto px-0 sm:px-2 md:px-4 lg:px-6 xl:px-8 py-6">
    {{-- Breadcrumb --}}
    <div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-0 -mt-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <span class="text-gray-400">/</span>
                    <a href="{{ route('pengelola.penyewa.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                        Data Penyewa
                    </a>
                </li>
                <li>
                    <span class="text-gray-400">/</span>
                    <span class="text-green-600 text-sm font-medium">Data Penyewa</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mt-4 mb-6">
        <a href="{{ route('pengelola.penyewa.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-green-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Data Penyewa
        </a>
    </div>

    {{-- Form Container --}}
    <form action="{{ route('pengelola.penyewa.update', $user->id_penyewa) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Penting untuk method update --}}

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI: FOTO PROFIL --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Foto Profil</h3>

                    <div class="relative w-40 h-40 mx-auto mb-4 group">
                        {{-- Preview Image --}}
                        <img id="preview-image"
                             src="{{ $user->foto_profil ? asset('storage/' . $user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_lengkap).'&background=random' }}"
                             alt="Preview"
                             class="w-full h-full rounded-full object-cover border-4 border-gray-100 shadow-md group-hover:opacity-75 transition-opacity">

                        {{-- Overlay Icon Camera --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-camera text-gray-800 text-3xl"></i>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mb-4">
                        Format: JPG, JPEG, PNG. Maks: 2MB.<br>
                        Biarkan kosong jika tidak ingin mengubah foto.
                    </p>

                    <label for="foto_profil" class="inline-block cursor-pointer px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-upload mr-2"></i> Pilih Foto Baru
                    </label>
                    <input type="file" name="foto_profil" id="foto_profil" class="hidden" onchange="previewFile()">

                    @error('foto_profil')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- KOLOM KANAN: FORM DATA DIRI --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-bold text-gray-800">Informasi Pribadi & Akun</h3>
                    </div>

                    <div class="p-6 space-y-6">

                        {{-- Nama Lengkap --}}
                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required
                                class="mt-1 py-2 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('nama_lengkap') @enderror">
                            @error('nama_lengkap') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Username --}}
                            <div>
                                <label for="username" class="block text-sm font-medium text-gray-700">Username <span class="text-red-500">*</span></label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-100 text-gray-900 sm:text-sm">@</span>
                                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" required
                                        class="flex-1 py-2 block w-full bg-gray-100 rounded-none rounded-r-md border-gray-300 focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                </div>
                                @error('username') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                    class="mt-1 py-2 block w-full rounded-md bg-gray-100 border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- No Telepon --}}
                            <div>
                                <label for="no_telp" class="block text-sm font-medium text-gray-700">No. Telepon <span class="text-red-500">*</span></label>
                                <input type="number" name="no_telp" id="no_telp" value="{{ old('no_telp', $user->no_telp) }}" required
                                    class="mt-1 block py-2 bg-gray-100 w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                @error('no_telp') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Asal --}}
                            <div>
                                <label for="asal" class="block text-sm font-medium text-gray-700">Asal / Kota</label>
                                <input type="text" name="asal" id="asal" value="{{ old('asal', $user->asal) }}"
                                    class="mt-1 py-2 bg-gray-100 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                @error('asal') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Jenis Kelamin --}}
                            <div>
                                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" id="jenis_kelamin" required class="mt-1 block w-full py-2 bg-gray-100 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div>
                                <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tanggal_lahir) }}"
                                    class="mt-1 py-2 bg-gray-100 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>

                            {{-- Usia (Opsional, atau bisa dihitung otomatis di backend) --}}
                            <div>
                                <label for="usia" class="block text-sm font-medium text-gray-700">Usia</label>
                                <input type="number" name="usia" id="usia" value="{{ old('usia', $user->usia) }}"
                                    class="mt-1 py-2 bg-gray-100 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="3" class="mt-1 block w-full bg-gray-100 rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">{{ old('alamat', $user->alamat) }}</textarea>
                            @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        {{-- Password (Opsional) --}}
                        {{-- <div class="border-t border-gray-100 pt-6 mt-6">
                            <h4 class="text-sm font-bold text-gray-900 mb-4">Ganti Password (Opsional)</h4>
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                <p class="mt-1 text-xs text-gray-400">Minimal 8 karakter.</p>
                                @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                        </div> --}}

                    </div>

                    {{-- Footer Buttons --}}
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-3">
                        <a href="{{ route('pengelola.penyewa.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Script sederhana untuk preview gambar saat dipilih
    function previewFile() {
        const preview = document.getElementById('preview-image');
        const file = document.getElementById('foto_profil').files[0];
        const reader = new FileReader();

        reader.addEventListener("load", function () {
            // convert image file to base64 string
            preview.src = reader.result;
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
