@extends('layout.admin')
@section('title', 'Data Pengelola')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-0 -mt-6 ml-3 sm:ml-0 lg:ml-0">
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
                <span class="text-green-600 text-sm font-medium">Data Pengelola</span>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full mx-auto px-3 sm:px-2 md:px-4 lg:px-0 xl:px-0 py-3 lg:py-0 grid">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between md:mt-6 mb-3 gap-4">
        <h2 class="text-2xl font-semibold text-gray-700">
            Data Pengelola
        </h2>

        <a href="{{ route('admin.pengelola.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition uppercase text-sm font-bold shadow-sm">
            + Tambah
        </a>
    </div>

    {{-- Tabel Container --}}
    <div class="flex flex-col w-full min-w-0 mb-6">
        <div class="w-full overflow-x-auto rounded-lg border border-neutral-700 bg-white">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-neutral-700">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Username</th>
                            <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Nama Lengkap</th>
                            <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Email</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-700 bg-white">
                        @forelse ($pengelolas as $pengelola)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">{{ $pengelola->username }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">{{ $pengelola->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">{{ $pengelola->email }}</td>
                            <td class="px-6 py-4 text-center text-sm font-medium whitespace-nowrap">
                                <form id="delete-form-{{ $pengelola->id_pengelola }}" action="{{ route('admin.pengelola.destroy', $pengelola->id_pengelola) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('admin.pengelola.show', $pengelola->id_pengelola) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-bold uppercase">Tampilkan</a>

                                    <span class="text-gray-300">|</span>

                                    <a href="{{ route('admin.pengelola.edit', $pengelola->id_pengelola) }}" class="text-amber-600 hover:text-amber-800 uppercase text-xs font-bold">Edit</a>

                                    <span class="text-gray-300">|</span>

                                    <button type="button" onclick="confirmDelete('{{ $pengelola->id_pengelola }}', '{{ $pengelola->nama_lengkap }}')" class="text-red-600 hover:text-red-800 uppercase text-xs font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500 italic">Data tidak ada.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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
            text: "Data pengelola \"" + userName + "\" akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
@endpush
