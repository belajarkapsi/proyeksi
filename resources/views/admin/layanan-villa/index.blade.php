@extends('layout.admin')
@section('title', 'Layanan Villa')

@section('content')
{{-- Navbar Breadcrumb --}}
<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8">
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-400 hover:text-green-600">
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

<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto grid">
    <div class="flex justify-between items-center py-4">
        <h2 class="text-2xl font-semibold text-gray-700">
            Daftar Layanan Villa {{ ucfirst($lokasi) }}
        </h2>

        <a href="{{ route('admin.cabanglayanan-villa.create', $lokasi) }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition uppercase text-sm font-bold">
            + Tambah Layanan
        </a>
    </div>

    {{-- Tabel --}}
    <div class="flex flex-col w-full min-w-0 mb-6">
        <div class="w-full overflow-x-auto rounded-lg border border-neutral-700 bg-white">
            <div class="inline-block min-w-full align-middle">
                <table class="min-w-full divide-y divide-neutral-700">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase">Nama Layanan</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase">Harga</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase">Deskripsi</th>
                            <th class="px-4 py-3 text-center text-sm font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-700 bg-white">
                        @forelse ($services as $service)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-center text-sm font-medium text-gray-800">{{ $service->name }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-center text-sm text-gray-500">{{ Str::limit($service->description, 50) ?: '-' }}</td>

                            <td class="px-4 py-3 text-center text-sm font-medium whitespace-nowrap">
                                <form id="delete-form-{{ $service->id }}" action="{{ route('admin.cabanglayanan-villa.destroy', ['lokasi' => $lokasi, 'layanan_villa' => $service->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')

                                    <a href="{{ route('admin.cabanglayanan-villa.edit', ['lokasi' => $lokasi, 'layanan_villa' => $service->id]) }}" class="uppercase text-amber-600 hover:text-amber-800 font-bold">Edit</a>
                                    <span class="text-gray-500">|</span>

                                    <button type="button" onclick="confirmDelete('{{ $service->id }}', '{{ $service->name }}')" class="uppercase text-red-600 hover:text-red-800 font-bold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                Belum ada data layanan untuk Villa ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $services->appends(['lokasi' => $lokasi])->links() }}
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
            text: "Layanan \"" + name + "\" akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
@endpush
