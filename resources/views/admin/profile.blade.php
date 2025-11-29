@extends('layout.admin')
@section('title', 'Profil Admin')

@section('content')

<div class="px-0 sm:px-1 py-3 lg:py-0 md:px-2 lg:px-4 -mt-8">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-green-600 transition-colors">
                    <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <span class="ms-1 text-sm font-medium text-gray-400 md:ms-2">Profil Saya</span>
                </div>
            </li>
        </ol>
    </nav>
</div>

<div class="w-full px-0 sm:px-2 md:px-4 lg:px-6 xl:px-10 mx-auto grid">

    {{-- 2. HEADER HALO --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Halo, {{ $admin->nama_lengkap }} 👋🏻</h1>
        <p class="mt-1 text-sm text-gray-500">
            Lihat atau Ubah Informasi Dirimu Pada Halaman Ini
        </p>
    </div>

    {{-- Alert Manual --}}
    @if (session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <div><strong class="font-bold">Sukses!</strong> <span class="block sm:inline">{{ session('success') }}</span></div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Sebelah Kiri --}}
        <div class="bg-gray-100 rounded-2xl p-6 h-fit shadow-sm">
            <div class="flex flex-col items-center mb-6">
                <div class="relative">
                    @if($admin->foto_profil)
                        <img id="img-preview" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md transition-opacity group-hover:opacity-90"
                            src="{{ asset('storage/' . $admin->foto_profil) }}?t={{ time() }}"
                            alt="{{ $admin->nama_lengkap }}">
                        @error('foto_profil') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    @else
                        <img id="img-preview" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md transition-opacity group-hover:opacity-90"
                            src="https://ui-avatars.com/api/?name={{ urlencode($admin->nama_lengkap) }}&background=random&size=128"
                            alt="Default Avatar">
                    @endif
                </div>
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $admin->nama_lengkap }}</h2>
                <p class="text-sm text-gray-500">Admin</p>
                <label for="foto_profil_input" class="text-xs text-green-600 font-medium mt-1 cursor-pointer hover:underline hover:text-green-800 transition-colors">Ubah Foto Profil</label>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-500 ">Username:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $admin->username }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Email:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $admin->email }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">No Telepon:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $admin->no_telp ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Sebelah Kanan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl p-8 shadow-2xl border border-gray-100">
            <h1 class="mb-5 text-3xl font-bold text-green-700 text-center ">Edit Profilmu:</h1>
            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input type="file" name="foto_profil" id="foto_profil_input" class="hidden" accept="image/*" onchange="previewImage(event)">

                <div class="space-y-6">

                    {{-- Nama Lengkap --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap<span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $admin->nama_lengkap) }}"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-gree\n-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Username --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Username<span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $admin->username) }}"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- Email (Boleh diedit oleh admin) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email<span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    {{-- No Telepon --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">No. Telepon<span class="text-red-500">*</span></label>
                        <input type="text" name="no_telp" value="{{ old('no_telp', $admin->no_telp) }}"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none">
                        @error('no_telp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" id="form-submit-btn" class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg shadow hover:bg-green-700 transition-all hover:shadow-lg hover:cursor-pointer hidden">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

@push('scripts')
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            document.getElementById('img-preview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
        document.getElementById('form-submit-btn').classList.remove('hidden');
    }

    // Auto-show button on change
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('form-submit-btn').classList.remove('hidden');
        });
    });

    // Auto hide alert
    const successAlert = document.getElementById('alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }, 3000);
    }
</script>
@endpush
@endsection
