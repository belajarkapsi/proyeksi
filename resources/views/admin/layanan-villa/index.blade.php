@extends('layout.admin')
@section('title', 'Layanan Villa')

@section('content')
{{-- Navbar Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <span class="text-gray-400">/</span>
                <span class="text-green-600 text-sm font-medium">Layanan Villa {{ ucfirst($lokasi) }}</span>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-6 mb-8 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Layanan Villa {{ ucfirst($lokasi) }}</h2>
            <p class="text-sm text-gray-500">Kelola fasilitas tambahan dan layanan ekstra untuk penyewa.</p>
        </div>

        <a href="{{ route('admin.cabanglayanan-villa.create', $lokasi) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-bold text-sm uppercase">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Layanan
        </a>
    </div>

    {{-- Grid Layout untuk Kartu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse ($services as $service)
            <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden relative">
                
                {{-- Decorative Element --}}
                <div class="absolute top-0 right-0 p-4">
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                        <i class="fa-solid fa-bell-concierge"></i>
                    </div>
                </div>

                <div class="p-5 flex flex-col h-full">
                    {{-- Nama & Harga --}}
                    <div class="mb-4 pr-10">
                        <h3 class="text-lg font-bold text-gray-900 leading-tight mb-1">{{ $service->name }}</h3>
                        <p class="text-green-600 font-extrabold text-xl">
                            Rp {{ number_format($service->price, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-6 flex-grow">
                        <p class="text-sm text-gray-500 line-clamp-3">
                            {{ $service->description ?: 'Tidak ada deskripsi untuk layanan ini.' }}
                        </p>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.cabanglayanan-villa.edit', ['lokasi' => $lokasi, 'layanan_villa' => $service->id]) }}" 
                           class="flex-1 inline-flex justify-center items-center py-2 text-sm font-bold text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                            <i class="far fa-edit mr-2"></i> Edit
                        </a>

                        <form id="delete-form-{{ $service->id }}" action="{{ route('admin.cabanglayanan-villa.destroy', ['lokasi' => $lokasi, 'layanan_villa' => $service->id]) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete('{{ $service->id }}', '{{ $service->name }}')" 
                                    class="w-full inline-flex justify-center items-center py-2 text-sm font-bold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                <i class="far fa-trash-alt mr-2"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-16 text-center bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <i class="fa-solid fa-box-open text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900">Belum ada layanan</h3>
                <p class="text-gray-500 max-w-xs mx-auto">Klik tombol tambah untuk mulai memasukkan layanan ekstra villa di {{ $lokasi }}.</p>
            </div>
        @endforelse
    </div>

    {{-- ✨ Footer Konten (Pagination) yang Responsif ✨ --}}
    <div class="mt-10 mb-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-200 pt-8">
        {{-- Info Status Data (Mobile: Urutan kedua, Desktop: Urutan pertama) --}}
        <div class="order-2 md:order-1 text-center md:text-left">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-semibold text-gray-800">{{ $services->firstItem() ?? 0 }}</span> 
                sampai <span class="font-semibold text-gray-800">{{ $services->lastItem() ?? 0 }}</span> 
                dari <span class="font-semibold text-gray-800">{{ $services->total() }}</span> layanan
            </p>
        </div>

        {{-- Navigasi Pagination (Mobile: Urutan pertama, Desktop: Urutan kedua) --}}
        <div class="order-1 md:order-2 w-full md:w-auto overflow-x-auto flex justify-center">
            <div class="px-2 pb-2 md:pb-0">
                {{ $services->appends(['lokasi' => $lokasi])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Layanan?',
            text: "Layanan \"" + name + "\" akan dihapus dari daftar secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush