@extends('layout.master')
@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if (session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <div>
                    <strong class="font-bold">Sukses!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
        </div>
    @elseif ($errors->any())
        <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <div class="flex items-center mb-1">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <strong class="font-bold">Terjadi Kesalahan!</strong>
            </div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Biodata (Kiri) -->
        <div class="bg-gray-100 rounded-2xl p-6 h-fit shadow-sm">
            <div class="flex flex-col items-center mb-6">
                <div class="relative">
                    @if($penyewa->foto_profil)
                        <img id="img-preview" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md transition-opacity group-hover:opacity-90"
                            src="{{ asset('storage/' . $penyewa->foto_profil) }}?t={{ time() }}"
                            alt="{{ $penyewa->nama_lengkap }}">
                    @else
                        <img id="img-preview" class="w-28 h-28 rounded-full object-cover border-4 border-white shadow-md transition-opacity group-hover:opacity-90"
                            src="https://ui-avatars.com/api/?name={{ urlencode($penyewa->nama_lengkap) }}&background=random&size=128"
                            alt="Default Avatar">
                    @endif
                </div>
                <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $penyewa->nama_lengkap }}</h2>
                <p class="text-sm text-gray-500">Penyewa</p>
                <label for="foto_profil_input" class="text-xs text-green-600 font-medium mt-1 cursor-pointer hover:underline hover:text-green-800 transition-colors">Ubah Foto Profil</label>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs text-gray-500 ">Email:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $penyewa->email }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">No Telepon:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $penyewa->no_telp ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Asal:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $penyewa->asal ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Jenis Kelamin:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $penyewa->jenis_kelamin ?? '-' }}</div>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 ">Usia:</label>
                    <div class="text-sm font-medium text-gray-800 border-b border-gray-400 pb-1">{{ $penyewa->usia ? $penyewa->usia . ' Tahun' : '-' }}</div>
                </div>
            </div>
        </div>

        @php
        $missingFields = session('missing_profile_fields', []);
        @endphp
        <!-- Form Edit (Kanan) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <input type="file" name="foto_profil" id="foto_profil_input" class="hidden" accept="image/*" onchange="previewImage(event)">

                <div class="space-y-6">
                    <div name="nama_lengkap">
                        <label for="nama_lengkap" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap<span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', $penyewa->nama_lengkap) }}"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('nama_lengkap') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="username">
                        <label for="username" class="block text-sm font-bold text-gray-700 mb-2">Username<span class="text-red-500">*</span></label>
                        <input type="username" name="username" id="username" value="{{ old('username', $penyewa->username) }}"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" required>
                        @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="email">
                        <label for="email" class="block text-sm font-bold text-gray-500 mb-2">Email (Tidak dapat diubah)</label>
                        <input type="email" value="{{ $penyewa->email }}" disabled
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm text-gray-500 cursor-not-allowed">
                        <p class="text-xs text-gray-400 mt-1">Hubungi admin jika ingin mengubah email.</p>
                    </div>

                    <div name="no_telepon">
                        <label for="no_telp" class="block text-sm font-bold text-gray-700 mb-2">No. Telepon<span class="text-red-500">*</span></label>
                        <input type="number" name="no_telp" id="no_telp" value="{{ old('no_telp', $penyewa->no_telp) }}" aria-describedby="helper-text-explanation"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all disabled:opacity-50 disabled:pointer-events-none" pattern="[0-9]{4}-[0-9]{4}-[0-9]{5}" required />
                        @error('no_telp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div name="asal">
                        <label for="asal" class="block text-sm font-bold text-gray-700 mb-2">Asal Kota/Daerah<span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="asal" id="asal" class="py-3 px-4 pe-9 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all" required>
                                <option value="" disabled {{ !$penyewa->kota_asal ? 'selected' : '' }}>Pilih Asal Kotamu</option>
                                @php
                                    $kotas = ['Jakarta', 'Makassar', 'Surabaya', 'Parepare', 'Pangkep', 'Sidrap', 'Pinrang', 'Barru', 'Maros'];
                                @endphp
                                @foreach ($kotas as $kota)
                                    <option value="{{ $kota }}" {{ old('asal', $penyewa->asal) == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('asal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        @if(in_array('asal', $missingFields) && empty(old('asal', $penyewa->asal)))
                        <span class="text-red-500 text-xs">Data belum diisi!</span>
                        @endif
                    </div>

                    <div name="alamat">
                        <label for="alamat" class="block text-sm font-bold text-gray-700 mb-2">Alamat<span class="text-red-500">*</span></label>
                        <textarea name="alamat" id="alamat" rows="2"
                            class="py-3 px-4 block w-full bg-gray-200 border-transparent rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white transition-all" required>{{ old('alamat', $penyewa->alamat) }}</textarea>
                        @error('alamat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                        @if(in_array('alamat', $missingFields) && empty(old('alamat', $penyewa->alamat)))
                        <span class="text-red-500 text-xs">Data belum diisi!</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div name="jenis_kelamin">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kelamin<span class="text-red-500">*</span></label>
                            <div class="flex flex-col space-y-2 mt-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="Laki-laki" id="gender_male"
                                        class="shrink-0 mt-0.5 border-gray-300 rounded-full text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                                        {{ old('jenis_kelamin', $penyewa->jenis_kelamin) == 'Laki-laki' ? 'checked' : '' }} required>
                                    <label for="gender_male" class="text-sm text-gray-500 ms-2">Laki-laki</label>
                                </div>

                                <div class="flex items-center">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan" id="gender_female"
                                        class="shrink-0 mt-0.5 border-gray-200 rounded-full text-green-600 focus:ring-green-500 disabled:opacity-50 disabled:pointer-events-none cursor-pointer"
                                        {{ old('jenis_kelamin', $penyewa->jenis_kelamin) == 'Perempuan' ? 'checked' : '' }}>
                                    <label for="gender_female" class="text-sm text-gray-500 ms-2">Perempuan</label>
                                </div>
                            </div>
                            @error('jenis_kelamin') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                            @if(in_array('jenis_kelamin', $missingFields) && empty(old('jenis_kelamin', $penyewa->jenis_kelamin)))
                                <span class="text-red-500 text-xs mt-1 block">Data belum diisi!</span>
                            @endif
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="bg-purple-50/50 p-4 rounded-xl border border-purple-100" name="tanggal_lahir">
                            <label for="tanggal_lahir" class="block text-sm font-bold text-gray-800 mb-2">Tanggal Lahir<span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $penyewa->tanggal_lahir) }}"
                                    class="py-3 px-4 block w-full bg-gray-100 border-green-500 rounded-lg text-sm focus:border-green-500 focus:ring-green-500 focus:bg-white" required>
                                <p class="text-xs text-gray-500 mt-1">Usia akan dihitung otomatis.</p>
                                @error('tanggal_lahir') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror

                                @if(in_array('tanggal_lahir', $missingFields) && empty(old('tanggal_lahir', $penyewa->tanggal_lahir)))
                                <span class="text-red-500 text-xs mt-1 block">Data belum diisi!</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Simpan -->
                    <div class="mt-8 flex justify-end">
                        <button type="submit" id="form-submit-btn" class="py-4 px-45 md:px-85 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none shadow-md transition-all hover:shadow-lg hover:cursor-pointer">
                            Simpan
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
            const output = document.getElementById('img-preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);

        // Munculkan tombol simpan jika user ganti foto
        document.getElementById('form-submit-btn').classList.remove('hidden');
    }

    // Opsional: Munculkan tombol simpan jika ada input lain yang berubah
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('form-submit-btn').classList.remove('hidden');
        });
    });

    // Durasi Notifikasi (Sukses)
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen alert sukses berdasarkan ID
        const successAlert = document.getElementById('alert-success');

        if (successAlert) {
            // Set timer 3 detik (3000 milidetik)
            setTimeout(function() {
                // Tambahkan transisi agar hilangnya smooth (pelan-pelan)
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = "0"; // Ubah opacity jadi 0 (transparan)

                // Tunggu transisi selesai (0.5 detik), lalu hapus elemen dari HTML
                setTimeout(function() {
                    successAlert.remove();
                }, 500);
            }, 2000);
        }
    });
    // Durasi Notifikasi (Gagal)
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen alert sukses berdasarkan ID
        const successAlert = document.getElementById('alert-error');

        if (successAlert) {
            // Set timer 3 detik (3000 milidetik)
            setTimeout(function() {
                // Tambahkan transisi agar hilangnya smooth (pelan-pelan)
                successAlert.style.transition = "opacity 0.5s ease";
                successAlert.style.opacity = "0"; // Ubah opacity jadi 0 (transparan)

                // Tunggu transisi selesai (0.5 detik), lalu hapus elemen dari HTML
                setTimeout(function() {
                    successAlert.remove();
                }, 500);
            }, 3000);
        }
    });

</script>
@endpush
@endsection
