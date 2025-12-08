@extends('layout.admin')
@section('title', 'Edit Data')

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
                <a href="{{ route('admin.cabangkamar.index', ['lokasi' => $lokasi]) }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">Data Kamar</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Edit Data</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-10">
    {{-- Header Card --}}
    <div class="bg-green-700 px-6 py-4 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i> Edit Data Kamar
        </h3>
        <a href="{{ route('admin.cabangkamar.index', $lokasi) }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm transition-colors border border-green-500">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Start --}}
    <form action="{{ route('admin.cabangkamar.update', ['lokasi' => $lokasi, 'kamar' => $kamar->id_kamar]) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- No Kamar --}}
            <div>
                <label for="no_kamar" class="block mb-2 text-sm font-medium text-gray-900">Nomor Kamar <span class="text-red-500">*</span></label>
                <input type="text" id="no_kamar" name="no_kamar" value="{{ old('no_kamar', $kamar->no_kamar) }}" placeholder="Contoh: A-01" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('no_kamar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Harga Kamar --}}
            <div>
                <label for="harga_kamar" class="block mb-2 text-sm font-medium text-gray-900">Harga per Malam (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="harga_kamar" name="harga_kamar" value="{{ old('harga_kamar', $kamar->harga_kamar) }}" placeholder="Masukkan harga..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('harga_kamar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Tipe Kamar --}}
            <div>
                <label for="tipe_kamar" class="block mb-2 text-sm font-medium text-gray-900">Tipe Kamar <span class="text-red-500">*</span></label>
                <select id="tipe_kamar" name="tipe_kamar" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                    <option value="Ekonomis" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Ekonomis' ? 'selected' : '' }}>Ekonomis</option>
                    <option value="Standar" {{ old('tipe_kamar', $kamar->tipe_kamar) == 'Standar' ? 'selected' : '' }}>Standar</option>
                </select>
                @error('tipe_kamar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Status Kamar --}}
            <div>
                <label for="status" class="block mb-2 text-sm font-medium text-gray-900">Status <span class="text-red-500">*</span></label>
                <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                    <option value="Tersedia" {{ old('status', $kamar->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Dihuni" {{ old('status', $kamar->status) == 'Dihuni' ? 'selected' : '' }}>Dihuni</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi (Full Width) --}}
            <div class="col-span-1 md:col-span-2">
                <label for="deskripsi" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi & Fasilitas <span class="text-red-500">*</span></label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" placeholder="Jelaskan fasilitas kamar...">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
                @error('deskripsi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="block w-full h-px bg-gray-200 col-span-1 md:col-span-2 my-2"></div>

            {{-- Foto Kamar --}}
            <div class="col-span-1 md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Foto Kamar</label>
                <div class="flex items-start gap-4">
                    {{-- Preview Gambar Lama/Baru --}}
                    <div class="shrink-0">
                        @if($kamar->gambar)
                            <img id="image-preview" src="{{ asset('storage/' . $kamar->gambar) }}" alt="Preview" class="w-24 h-24 rounded-lg object-cover border border-gray-300 shadow-sm">
                        @else
                            <img id="image-preview" src="" alt="Preview" class="hidden w-24 h-24 rounded-lg object-cover border border-gray-300 shadow-sm">
                            <div id="no-image-placeholder" class="w-24 h-24 rounded-lg border border-gray-300 bg-gray-50 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="w-full">
                        <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="gambar" name="gambar" type="file" accept="image/*" onchange="previewImage()">
                        <p class="mt-1 text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</p>
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, JPEG (MAX. 2MB).</p>
                        @error('gambar') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
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

@push('scripts')
{{-- Script Preview Image --}}
<script>
    function previewImage() {
        const image = document.querySelector('#gambar');
        const imgPreview = document.querySelector('#image-preview');
        const placeholder = document.querySelector('#no-image-placeholder');

        if(image.files && image.files[0]){
            const oFReader = new FileReader();
            oFReader.readAsDataURL(image.files[0]);

            oFReader.onload = function(oFREvent) {
                imgPreview.src = oFREvent.target.result;
                imgPreview.classList.remove('hidden'); // Munculkan img
                if(placeholder) placeholder.classList.add('hidden'); // Sembunyikan placeholder kotak abu
            }
        }
    }
</script>
@endpush

