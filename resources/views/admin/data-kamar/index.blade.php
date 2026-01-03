@extends('layout.admin')
@section('title', 'Data Kamar')

@section('content')
{{-- Navbar Breadcrumb --}}
<div class="px-0 sm:px-1 py-6 md:py-3 lg:py-0 md:px-2 lg:px-0 -mt-6 ml-3 sm:ml-0 lg:ml-0">
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
                <span class="text-green-600 text-sm font-medium">Data Kamar {{ Str::ucfirst($lokasi) }}</span>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full mx-auto px-3 sm:px-2 md:px-4 lg:px-0 xl:px-0 py-3 lg:py-0">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-6 mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Data Kamar {{ Str::ucfirst($lokasi) }}</h2>
            <p class="text-sm text-gray-500">Kelola informasi seluruh unit kamar di cabang ini.</p>
        </div>

        <a href="{{ route('admin.cabangkamar.create', ['lokasi' => $lokasi]) }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm font-semibold text-sm uppercase">
            <i class="fa-solid fa-plus mr-2"></i> Tambah Kamar
        </a>
    </div>

    {{-- Grid Kartu --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse ($kamars as $kamar)
            <div class="group bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col overflow-hidden">

                {{-- Bagian Gambar & Badge Status --}}
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    @if($kamar->gambar)
                        <img src="{{ asset('storage/' . $kamar->gambar) }}" alt="Kamar" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="fa-regular fa-image fa-2x mb-2"></i>
                            <span class="text-xs">Tidak ada gambar</span>
                        </div>
                    @endif

                    {{-- Status Badge --}}
                    <div class="absolute top-3 right-3">
                        @php
                        $status = $kamar->status ?? '';
                        if($status == 'Tersedia') {
                            $badge = 'bg-green-100 text-green-700 border-green-200';
                        } elseif ($status == 'Perbaikan') {
                            $badge = 'bg-yellow-100 text-yellow-700 border-yellow-200';
                        } else {
                            $badge = 'bg-red-100 text-red-700 border-red-200';
                        }
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold shadow-sm border {{ $badge }}">
                            {{ $kamar->status }}
                        </span>
                    </div>

                    {{-- Kategori Badge (Villa/Lainnya) --}}
                    <div class="absolute bottom-3 left-3">
                        @php
                            $kategori = Str::lower($kamar->cabang->kategori_cabang ?? '');
                            $badgeClass = ($kategori === 'villa')
                                ? 'bg-yellow-500 text-white'
                                : 'bg-purple-600 text-white';
                        @endphp
                        <span class="{{ $badgeClass }} text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded shadow-lg">
                            {{ $kamar->cabang->kategori_cabang ?? '-' }}
                        </span>
                    </div>
                </div>

                {{-- Konten Informasi --}}
                <div class="p-4 flex flex-col grow">
                    <div class="flex justify-between items-start mb-1">
                        <h3 class="text-lg font-bold text-gray-900 leading-tight">No. {{ $kamar->no_kamar }}</h3>
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $kamar->tipe_kamar }}</span>
                    </div>

                    <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                        Cabang: {{ $kamar->cabang->lokasi ?? '-' }}
                    </p>

                    <div class="mt-auto">
                        <p class="text-green-600 font-bold text-lg">
                            Rp {{ number_format($kamar->harga_kamar, 0, ',', '.') }}
                            <span class="text-gray-400 text-xs font-normal">/malam</span>
                        </p>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-50">
                            <a href="{{ route('admin.cabangkamar.edit', ['lokasi' => $lokasi, 'kamar' => $kamar->id_kamar]) }}"
                                class="flex-1 inline-flex justify-center items-center py-2 text-sm font-bold text-amber-600 bg-amber-50 rounded-lg hover:bg-amber-100 transition">
                                <i class="far fa-edit mr-2"></i> Edit
                            </a>

                            <form id="delete-form-{{ $kamar->id_kamar }}" action="{{ route('admin.cabangkamar.destroy',['lokasi' => $lokasi, 'kamar' => $kamar->id_kamar]) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete('{{ $kamar->id_kamar }}', '{{ $kamar->no_kamar }}')"
                                        class="w-full inline-flex justify-center items-center py-2 text-sm font-bold text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    <i class="far fa-trash-alt mr-2"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-gray-50 rounded-xl border border-dashed border-gray-300 py-12 text-center">
                <i class="fa-solid fa-folder-open text-gray-300 text-4xl mb-3"></i>
                <p class="text-gray-500 font-medium">Data kamar belum tersedia untuk lokasi ini.</p>
            </div>
        @endforelse
    </div>

    {{-- ✨ Footer Konten (Pagination) yang Responsif ✨ --}}
    <div class="mt-10 mb-12 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-200 pt-8">
        {{-- Info Status Data --}}
        <div class="order-2 md:order-1 text-center md:text-left">
            <p class="text-sm text-gray-500">
                Menampilkan <span class="font-semibold text-gray-800">{{ $kamars->firstItem() ?? 0 }}</span>
                sampai <span class="font-semibold text-gray-800">{{ $kamars->lastItem() ?? 0 }}</span>
                dari <span class="font-semibold text-gray-800">{{ $kamars->total() }}</span> unit kamar
            </p>
        </div>

        {{-- Navigasi Pagination --}}
        <div class="order-1 md:order-2 w-full md:w-auto overflow-x-auto flex justify-center">
            <div class="px-2 pb-2 md:pb-0">
                {{ $kamars->appends(['lokasi' => $lokasi])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, noKamar) {
        Swal.fire({
            title: 'Hapus Kamar ' + noKamar + '?',
            text: "Seluruh data terkait kamar ini akan dihapus permanen dan tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
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
