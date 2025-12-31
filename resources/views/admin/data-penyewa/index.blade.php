@extends('layout.admin')
@section('title', 'Data Penyewa')

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
                <span class="text-green-600 text-sm font-medium">Data Penyewa</span>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto grid">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mt-6 mb-6 gap-4">
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
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase whitespace-nowrap tracking-wider">Aksi</th>
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
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-medium whitespace-nowrap">
                                <form id="delete-form-{{ $user->id_penyewa }}" action="{{ route('penyewa.destroy', $user->id_penyewa) }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    @method('DELETE')

                                    {{-- Tombol Tampilkan (Detail) --}}
                                    <a href="{{ route('penyewa.show', $user->id_penyewa) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition text-xs font-bold uppercase">
                                        Tampilkan
                                    </a>
                                    
                                    <span class="text-gray-300">|</span>
                                    
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('penyewa.edit', $user->id_penyewa) }}" class="text-green-600 hover:text-green-800 uppercase text-xs font-bold transition">
                                        Edit
                                    </a>
                                    
                                    <span class="text-gray-300">|</span>

                                    {{-- Tombol Hapus --}}
                                    <button type="button" onclick="confirmDelete('{{ $user->id_penyewa }}', '{{ $user->nama_lengkap }}')" class="text-red-600 hover:text-red-800 uppercase text-xs font-bold transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm text-gray-500 italic">
                                Belum ada data penyewa yang terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ✨ Footer Pagination & Info Data Responsif ✨ --}}
        <div class="mt-8 mb-4 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-gray-100 pt-6">
            {{-- Info Data --}}
            <div class="text-sm text-gray-500 order-2 md:order-1 text-center md:text-left">
                Menampilkan 
                <span class="font-semibold text-gray-800">{{ $users->firstItem() ?? 0 }}</span> 
                sampai 
                <span class="font-semibold text-gray-800">{{ $users->lastItem() ?? 0 }}</span> 
                dari 
                <span class="font-semibold text-gray-800">{{ $users->total() }}</span> penyewa
            </div>

            {{-- Navigasi Pagination --}}
            <div class="order-1 md:order-2 w-full md:w-auto overflow-x-auto flex justify-center">
                <div class="px-2 pb-2 md:pb-0">
                    {{ $users->links() }}
                </div>
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
            text: "Data penyewa \"" + userName + "\" akan dihapus permanen!",
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