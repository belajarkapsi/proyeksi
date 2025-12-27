@extends('layout.pengelola')
@section('title', 'Edit Layanan Villa')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-4 mb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <a href="{{ route('pengelola.kamar.index') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">Data Kamar & Layanan</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Edit Service</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-10">
    {{-- Alert Error Validation --}}
    @if ($errors->any())
        <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative border-t-0 border-l-0 border-r-0" role="alert">
            <div class="flex items-center mb-1">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <strong class="font-bold">Periksa Kembali Inputan Anda!</strong>
            </div>
            <ul class="list-disc list-inside text-sm pl-8">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i> Edit Layanan Villa
        </h3>
        <a href="{{ route('pengelola.kamar.index') }}" class="text-white hover:bg-blue-800 px-3 py-1 rounded-md text-sm transition-colors border border-blue-500">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Form Start --}}
    <form action="{{ route('pengelola.service.update', $service->id) }}" method="POST" class="p-6 md:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama Layanan --}}
            <div>
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900">Nama Layanan <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" placeholder="Contoh: Extra Bed" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Harga --}}
            <div>
                <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Harga Sewa (Rp) <span class="text-red-500">*</span></label>
                <input type="number" id="price" name="price" value="{{ old('price', $service->price) }}" placeholder="Masukkan harga..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Stok --}}
            <div>
                <label for="stock" class="block mb-2 text-sm font-medium text-gray-900">Stok Tersedia (Unit) <span class="text-red-500">*</span></label>
                <input type="number" id="stock" name="stock" value="{{ old('stock', $service->stock) }}" placeholder="Jumlah unit..." class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
                <p class="mt-1 text-xs text-gray-500">Jumlah maksimal item fisik yang Anda miliki.</p>
                @error('stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi (Full Width) --}}
            <div class="col-span-1 md:col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Layanan</label>
                <textarea id="description" name="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500" placeholder="Deskripsi singkat layanan...">{{ old('description', $service->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="block w-full h-px bg-gray-200 col-span-1 md:col-span-2 my-6"></div>

        {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-3">
            <button type="reset" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-green-700 focus:z-10 focus:ring-2 focus:ring-green-500 transition-colors">
                Reset
            </button>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
