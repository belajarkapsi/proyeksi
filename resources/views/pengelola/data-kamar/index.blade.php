@extends('layout.pengelola')
@section('title', 'Data Kamar')

@section('content')
{{-- Navbar --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-4">
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
                <span class="text-green-600 text-sm font-medium">Data Kamar</span>
            </li>
        </ol>
    </nav>
</div>

{{-- Konten --}}
<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto">

    <a href="{{ route('pengelola.dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 mt-5 hover:text-green-600 text-sm transition-colors">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-semibold text-gray-700">
            Data Kamar
        </h2>

        <a href="{{ route('pengelola.kamar.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition uppercase text-sm font-bold">
            <i class="fa-solid fa-plus text-sm"></i> Tambah
        </a>

    </div>


    {{-- CONTAINER TABEL YANG AMAN UNTUK MOBILE --}}
    <div class="flex flex-col w-full min-w-0 mb-6">
        <div class="w-full overflow-x-auto rounded-lg border border-neutral-700 bg-white">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-neutral-700">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- Gunakan whitespace-nowrap agar teks tidak turun ke bawah --}}
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase whitespace-nowrap">No. Kamar</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase whitespace-nowrap">Gambar</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase whitespace-nowrap">Tipe</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase whitespace-nowrap">Harga</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase whitespace-nowrap">Status</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-700 bg-white">
                        @forelse ($kamars as $kamar)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-800 whitespace-nowrap">{{ $kamar->no_kamar }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($kamar->gambar)
                                    {{-- Menampilkan gambar dari folder storage --}}
                                    <img src="{{ asset('storage/' . $kamar->gambar) }}" alt="Kamar {{ $kamar->no_kamar }}" class="h-12 w-16 object-cover rounded border border-gray-200 mx-auto">
                                @else
                                    {{-- Placeholder jika tidak ada gambar --}}
                                    <div class="h-12 w-16 bg-gray-100 rounded border border-gray-200 mx-auto flex items-center justify-center text-gray-400">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-800 whitespace-nowrap">{{ $kamar->tipe_kamar }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 whitespace-nowrap">Rp {{ number_format($kamar->harga_kamar, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center text-sm whitespace-nowrap">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $kamar->status == 'Tersedia' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $kamar->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-medium whitespace-nowrap">
                                <form id="delete-form-{{ $kamar->id_kamar }}" action="{{ route('pengelola.kamar.destroy', $kamar->id_kamar) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')

                                    @can('update', $kamar)
                                    <a href="{{ route('pengelola.kamar.edit', $kamar->id_kamar) }}" class="uppercase text-amber-600 hover:text-amber-800">Edit</a>
                                    @endcan
                                    <span class="text-gray-500">|</span>

                                    @can('delete', $kamar)
                                    <button type="button" onclick="confirmDelete('{{ $kamar->id_kamar }}', '{{ $kamar->no_kamar }}')" class="uppercase text-red-600 hover:text-red-800 font-bold">Hapus</button>
                                    @endcan
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-gray-500">Data tidak ada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Pagination Links --}}
        <div class="mt-4">
            {{ $kamars->links() }}
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data kamar \"" + userName + "\" akan dihapus permanen! Data yang dihapus tidak bisa dikembalikan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33', // Warna merah untuk tombol hapus
            cancelButtonColor: '#3085d6', // Warna biru untuk batal
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik "Ya, Hapus!", cari form berdasarkan ID unik dan submit
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
