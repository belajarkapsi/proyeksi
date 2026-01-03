@extends('layout.admin')
@section('title', 'Data Penyewa')

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
                    <span class="ms-1 text-sm font-bold text-green-600 md:ms-2">Data Penyewa</span>
                </div>
            </li>
        </ol>
    </nav>

<div class="w-full mx-auto px-3 sm:px-2 md:px-4 lg:px-0 xl:px-0 py-3 lg:py-0 grid">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between md:mt-6 mb-3 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">
            Data Penyewa
        </h2>
    </div>

    {{-- Tabel Container --}}
    <div class="flex flex-col w-full min-w-0 mb-6">
        <div class="w-full overflow-x-auto rounded-lg border border-neutral-700 bg-white shadow-sm">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-neutral-700">
                    <thead class="bg-gray-50">
                        <tr>
                            {{-- Hanya 4 Kolom Utama Sesuai Permintaan --}}
                            <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase whitespace-nowrap tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase whitespace-nowrap tracking-wider">No. Telp</th>
                            <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase whitespace-nowrap tracking-wider">Email</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase whitespace-nowrap tracking-wider md:pl-8 md:px-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-700 bg-white">
                        @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                                {{ $user->nama_lengkap }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                {{ $user->no_telp }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-gray-700">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $user->email }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Detail --}}
                                <a href="{{ route('penyewa.show', $user->id_penyewa) }}" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-200" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                
                                {{-- Edit --}}
                                <a href="{{ route('penyewa.edit', $user->id_penyewa) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all duration-200" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>

                                {{-- Hapus --}}
                                <form id="delete-form-{{ $user->id_penyewa }}" action="{{ route('penyewa.destroy', $user->id_penyewa) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('penyewa.show', $user->id_penyewa) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-bold uppercase">Tampilkan</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('penyewa.edit', $user->id_penyewa) }}" class="text-green-600 hover:text-green-800 uppercase text-xs font-bold transition">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" onclick="confirmDelete('{{ $user->id_penyewa }}', '{{ $user->nama_lengkap }}')" class="text-red-500 hover:text-red-800 uppercase text-xs font-bold transition cursor-pointer">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <p class="text-gray-500 text-lg font-medium">Belum ada data penyewa</p>
                                <p class="text-gray-400 text-sm">Data yang anda cari tidak ditemukan atau belum diinput.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi Pagination --}}
            <div class="order-1 md:order-2 w-full md:w-auto overflow-x-auto flex justify-center">
                <div class="px-2 pb-2 md:pb-0">
                    {{ $users->links() }}
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
            title: 'Hapus Data?',
            text: "Data penyewa \"" + userName + "\" akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Sekarang!',
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
