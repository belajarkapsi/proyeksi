@extends('layout.admin')
@section('title', 'Edit Informasi Cabang')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-gray-400 hover:text-green-600">
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <a href="{{ route('admin.cabanginformasi-cabang.index', ['lokasi' => $lokasi]) }}" class="text-sm font-medium text-gray-400 hover:text-green-600">Informasi Cabang {{ ucfirst($lokasi) }}</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Edit Informasi</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-green-700 px-6 py-4 flex justify-between items-center rounded-t-lg">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-building"></i> Edit Cabang: {{ $cabang->nama_cabang }}
        </h3>
        <a href="{{ route('admin.cabanginformasi-cabang.index', $lokasi) }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm border border-green-500">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.cabanginformasi-cabang.update', ['lokasi' => $lokasi, 'informasi_cabang' => $cabang->id_cabang]) }}" method="POST" enctype="multipart/form-data" class="p-6 bg-white border border-gray-200 rounded-b-lg shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama Cabang --}}
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Nama Cabang <span class="text-red-500">*</span></label>
                <input type="text" name="nama_cabang" value="{{ old('nama_cabang', $cabang->nama_cabang) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
            </div>

            {{-- Jumlah Kamar --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Jumlah Kamar <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_kamar" value="{{ old('jumlah_kamar', $cabang->jumlah_kamar) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
            </div>

            {{-- Kategori --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori_cabang" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5">
                    <option value="kost" {{ old('kategori_cabang', $cabang->kategori_cabang) == 'kost' ? 'selected' : '' }}>Kost</option>
                    <option value="villa" {{ old('kategori_cabang', $cabang->kategori_cabang) == 'villa' ? 'selected' : '' }}>Villa</option>
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Cabang</label>
                <textarea name="deskripsi" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500">{{ old('deskripsi', $cabang->deskripsi) }}</textarea>
            </div>

            <div class="block w-full h-px bg-gray-200 md:col-span-2 my-2"></div>

            {{-- Foto Cabang --}}
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900">Foto Cabang</label>
                <div class="flex items-start gap-4">
                    <div class="shrink-0">
                        @if($cabang->gambar_cabang && $cabang->gambar_cabang != 'dummy.jpg')
                            <img id="preview" src="{{ asset('storage/' . $cabang->gambar_cabang) }}" class="w-32 h-20 object-cover rounded-lg border border-gray-300">
                        @else
                            <div class="w-32 h-20 bg-gray-100 rounded-lg border border-gray-300 flex items-center justify-center text-gray-400">
                                <i class="fa-regular fa-image text-2xl"></i>
                            </div>
                        @endif
                    </div>
                    <div class="w-full">
                        <input type="file" name="gambar_cabang" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG (Max. 2MB). Kosongkan jika tidak ingin mengubah gambar.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
