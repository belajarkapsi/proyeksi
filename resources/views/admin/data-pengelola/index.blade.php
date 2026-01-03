@extends('layout.admin')
@section('title', 'Data Pengelola')

@section('content')
{{-- Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-0 -mt-6 ml-3 sm:ml-0 lg:ml-0">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition-colors">
                    <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ms-1 text-sm font-bold text-green-600 md:ms-2">Data Pengelola</span>
                </div>
            </li>
        </ol>
    </nav>

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

                                {{-- Delete --}}
                                <form id="delete-form-{{ $pengelola->id_pengelola }}" action="{{ route('admin.pengelola.destroy', $pengelola->id_pengelola) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $pengelola->id_pengelola }}', '{{ $pengelola->nama_lengkap }}')" class="text-red-500 hover:text-red-700 transition-colors uppercase font-bold text-xs hover:bg-red-50 rounded-lg" title="Hapus Pengelola">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <p class="text-gray-500 text-sm italic">Belum ada data pengelola yang terdaftar.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId, userName) {
        Swal.fire({
            title: 'Hapus Akun Pengelola?',
            text: "Akses login untuk \"" + userName + "\" akan dicabut secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            borderRadius: '12px'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
@endpush
