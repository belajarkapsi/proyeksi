@extends('layout.admin')
@section('title', 'Edit Layanan Villa')

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
                <a href="{{ route('admin.cabanglayanan-villa.index', ['parepare']) }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">Data Layanan Villa</a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Edit Data</span>
            </li>
        </ol>
    </nav>
</div>

<div class="max-w-4xl mx-auto bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden mb-10">
    <div class="bg-green-700 px-6 py-4 flex justify-between items-center rounded-t-lg">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square"></i> Edit Layanan Villa
        </h3>
        <a href="{{ route('admin.cabanglayanan-villa.index', $lokasi) }}" class="text-white hover:bg-green-600 px-3 py-1 rounded-md text-sm border border-green-500">
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.cabanglayanan-villa.update', ['lokasi' => $lokasi, 'layanan_villa' => $service->id]) }}" method="POST" class="p-6 bg-white border border-gray-200 rounded-b-lg shadow-sm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6">
            {{-- Nama Layanan --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Nama Layanan <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $service->name) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
            </div>

            {{-- Harga --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price', $service->price) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-green-500 focus:border-green-500 block w-full p-2.5" required>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900">Deskripsi (Opsional)</label>
                <textarea name="description" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-green-500 focus:border-green-500">{{ old('description', $service->description) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
