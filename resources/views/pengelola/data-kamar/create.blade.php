@extends('layout.pengelola')
@section('title', 'Tambah Data Kamar')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-4 mb-6">
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
                <a href="{{ route('pengelola.kamar.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">Data Kamar</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Tambah Data</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
    {{-- Alert Error Validation --}}
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
            <i class="fa-solid fa-bed"></i> Tambah Kamar
        </h3>
        {{-- Tombol Kembali membawa parameter lokasi --}}
        <a href="{{ route('pengelola.kamar.index') }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm transition-colors border border-green-500">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Body Form --}}
    <form action="{{ route('pengelola.kamar.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- No Kamar --}}
            <div>
                <label for="no_kamar" class="block mb-2 text-sm font-medium text-gray-900">Nomor Kamar <span class="text-red-500">*</span></label>
                <input type="text" id="no_kamar" name="no_kamar" value="{{ old('no_kamar') }}" placeholder="Contoh: A-01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('no_kamar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Harga Kamar --}}
            <div>
                <label for="harga_kamar" class="block mb-2 text-sm font-medium text-gray-900">Harga per Malam (Rp)<span class="text-red-500">*</span></label>
                <input type="number" id="harga_kamar" name="harga_kamar" value="{{ old('harga_kamar') }}" placeholder="Masukkan harga..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('harga_kamar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Tipe Kamar --}}
            <div>
                <label for="tipe_kamar" class="block mb-2 text-sm font-medium text-gray-900">Tipe Kamar<span class="text-red-500">*</span></label>
                <select id="tipe_kamar" name="tipe_kamar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                    <option value="" disabled selected>Pilih Tipe...</option>
                    <option value="Ekonomis" {{ old('tipe_kamar') == 'Ekonomis' ? 'selected' : '' }}>Ekonomis</option>
                    <option value="Standar" {{ old('tipe_kamar') == 'Standar' ? 'selected' : '' }}>Standar</option>
                </select>
                @error('tipe_kamar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Status Kamar --}}
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status Awal<span class="text-red-500">*</span></label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                    <option value="Tersedia" {{ old('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Dihuni" {{ old('status') == 'Dihuni' ? 'selected' : '' }}>Dihuni</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi (Full Width) --}}
            <div class="col-span-1 md:col-span-2">
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi & Fasilitas<span class="text-red-500">*</span></label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" placeholder="Jelaskan fasilitas kamar (AC, TV, Wi-Fi, dll)...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Foto Kamar --}}
            <div class="col-span-1 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Foto Kamar</label>

                {{-- Preview Image Container --}}
                <div class="flex items-start gap-4">
                    <div id="image-preview-container" class="hidden shrink-0">
                        <img id="image-preview" src="#" alt="Preview" class="w-24 h-24 rounded-lg object-cover border border-gray-300 shadow-sm">
                    </div>

                    <div class="w-full">
                        <input type="file" name="gambar" id="gambar" onchange="previewImage()" class="block w-full border border-gray-200 shadow-sm rounded-lg text-sm focus:z-10 focus:border-green-500 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none file:bg-gray-50 file:border-0 file:me-4 file:py-3 file:px-4 cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG (Max. 2MB).</p>
                        @error('gambar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="block w-full h-px bg-gray-200 col-span-1 md:col-span-2 my-2"></div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-3 mt-4">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-colors">
                Reset
            </button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 transition-colors">
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Script untuk Preview Gambar --}}
<script>
    function previewImage() {
        const image = document.querySelector('#gambar');
        const imgPreview = document.querySelector('#image-preview');
        const previewContainer = document.querySelector('#image-preview-container');

        if(image.files && image.files[0]){
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
                previewContainer.classList.remove('hidden'); // Tampilkan gambar
            }
        }
    }
</script>
@endpush
