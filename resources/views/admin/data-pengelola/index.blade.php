@extends('layout.admin')
@section('title', 'Data Pengelola')

@section('content')
{{-- Navbar --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8">
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
                <span class="text-green-600 text-sm font-medium">Data Pengelola</span>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto grid">
    <h2 class="py-4 text-2xl font-semibold text-gray-700">
        Data Pengelola
    </h2>

    {{-- CONTAINER TABEL YANG AMAN UNTUK MOBILE --}}
    <div class="flex flex-col w-full min-w-0 mb-6">
        <div class="w-full overflow-x-auto rounded-lg border border-neutral-700 bg-white">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-neutral-700">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- Gunakan whitespace-nowrap agar teks tidak turun ke bawah --}}
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Username</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Nama Lengkap</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap">No. Telp</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Email</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Tanggal Lahir</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap hidden sm:table-cell">Usia</th>
                            <th class="px-4 py-3 text-start text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Jenis Kelamin</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-700 bg-white">
                        @forelse ($pengelolas as $pengelola)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $pengelola->username }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $pengelola->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap">{{ $pengelola->no_telp }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap">{{ $pengelola->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap">{{ $pengelola->tanggal_lahir }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap hidden sm:table-cell">{{ $pengelola->usia }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 whitespace-nowrap">{{ $pengelola->jenis_kelamin }}</td>
                            <td class="px-4 py-3 text-center text-sm font-medium whitespace-nowrap">
                                <form id="delete-form-{{ $pengelola->id_pengelola }}" action="{{ route('pengelola.destroy', $pengelola->id_pengelola) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('pengelola.show', $pengelola->id_pengelola) }}" class="uppercase text-blue-600 hover:text-blue-800">Lihat</a>
                                    <span class="text-gray-500">|</span>
                                    <a href="{{ route('pengelola.edit', $pengelola->id_pengelola) }}" class="uppercase text-amber-600 hover:text-amber-800">Edit</a>
                                    <span class="text-gray-500">|</span>

                                    <button type="button" onclick="confirmDelete('{{ $pengelola->id_pengelola }}', '{{ $pengelola->nama_lengkap }}')" class="uppercase text-red-600 hover:text-red-800 font-bold">Hapus</button>
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
    </div>

    <a href="{{ route('pengelola.create') }}" class="uppercase text-green-600 hover:text-green-800">Tambah</a>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data pengelola \"" + userName + "\" akan dihapus permanen! Data yang dihapus tidak bisa dikembalikan.",
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
@endpush
