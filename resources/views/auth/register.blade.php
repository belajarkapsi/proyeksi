<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- UBAH TITLE --}}
    <title>Halaman Daftar</title> 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased text-gray-900">
    <div class="min-h-screen w-full relative">
        <img src="{{ asset('images/background.jpg') }}" alt="Background" class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none select-none blur-sm">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
            <div class="w-full max-w-6xl rounded-2xl overflow-hidden bg-white/10 backdrop-blur-md shadow-2xl ring-1 ring-black/10">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <!-- SISI KIRI (TIDAK PERLU DIUBAH) -->
                    <div class="p-8 md:p-12 flex flex-col justify-center items-center md:items-start text-center md:text-left relative overflow-hidden">
                        <div class="absolute top-10 ml-20 md:ml-20 transform -translate-x-1/2 md:translate-x-0 opacity-20 md:opacity-30 scale-150 pointer-events-none select-none">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Background" class="object-contain w-72 h-72 md:w-96 md:h-96">
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-4 relative z-10">
                            Selamat Datang di<br class="hidden md:inline"> Pondok Siti Hajar
                        </h1>
                        <p class="text-sm md:text-base text-white/85 max-w-xl relative z-10">
                            Pondok Siti Hajar merupakan jaringan penginapan yang menyediakan layanan sewa kamar harian dan bulanan bagi masyarakat, mahasiswa, dan wisatawan. Sistem ini mengintegrasikan pemesanan, validasi pembayaran, dan manajemen antar cabang sehingga operasional lebih efisien dan terpusat.
                        </p>
                    </div>

                    <!-- KANAN: FORM REGISTRASI -->
                    <div class="p-6 md:p-12 flex items-center justify-center">
                        <div class="w-full max-w-md">
                            <div class="bg-green-800/80 border border-white/10 rounded-xl p-6 md:p-8 shadow-xl">
                                
                                {{-- UBAH JUDUL FORM --}}
                                <h2 class="text-2xl md:text-3xl font-bold text-white text-center mb-4">Daftar Akun</h2>
                                
                                @if ($errors->any())
                                    <div class="mb-4 px-4 py-3 rounded bg-red-600/20 text-red-100 text-sm">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                
                                {{-- UBAH ACTION FORM --}}
                                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                                    @csrf
                                    
                                    {{-- TAMBAH: NAMA LENGKAP --}}
                                    <div>
                                        <label for="nama_lengkap" class="block font-medium text-sm text-gray-200 mb-1">Nama Lengkap</label>
                                        @error('nama_lengkap')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg></span>
                                            <input id="nama_lengkap" name="nama_lengkap" type="text" required autofocus value="{{ old('nama_lengkap') }}" placeholder="Masukkan Nama Lengkap" class="w-full px-10 py-3 rounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        </div>
                                    </div>
                                    
                                    {{-- TAMBAH: NOMOR TELEPON --}}
                                    <div>
                                        <label for="no_telp" class="block font-medium text-sm text-gray-200 mb-1">Nomor Telepon</label>
                                        @error('no_telp')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg></span>
                                            <input id="no_telp" name="no_telp" type="tel" required value="{{ old('no_telp') }}" placeholder="Contoh: 08123456789" class="w-full px-10 py-3 rounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        </div>
                                    </div>
                                    
                                    {{-- TAMBAH: EMAIL --}}
                                    <div>
                                        <label for="email" class="block font-medium text-sm text-gray-200 mb-1">Email</label>
                                        @error('email')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" /></svg></span>
                                            <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="Masukkan Alamat Email" class="w-full px-10 py-3 rounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        </div>
                                    </div>

                                    {{-- UBAH LABEL & PLACEHOLDER USERNAME --}}
                                    <div>
                                        <label for="username" class="block font-medium text-sm text-gray-200 mb-1">Username</label>
                                        @error('username')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg></span>
                                            <input id="username" name="username" type="text" required value="{{ old('username') }}" placeholder="Buat Username Anda" class="w-full px-10 py-3 rounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        </div>
                                    </div>

                                    {{-- INPUT PASSWORD --}}
                                    <div>
                                        <label for="password" class="block font-medium text-sm text-gray-200 mb-1">Password</label>
                                        @error('password')
                                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/></svg></span>
                                            <input id="password" name="password" type="password" required placeholder="Buat Password (Min. 8 Karakter)" class="w-full px-10 py-3 rounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        </div>
                                    </div>
                                    
                                    {{-- TAMBAH: KONFIRMASI PASSWORD --}}
                                    <div>
                                        <label for="password_confirmation" class="block font-medium text-sm text-gray-200 mb-1">Konfirmasi Password</label>                        
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 flex items-center pl-3"><svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/></svg></span>
                                            <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Ketik Ulang Password Anda" class="w-full px-10 py-3 rounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        </div>
                                    </div>
                                    
                                    {{-- UBAH TOMBOL SUBMIT --}}
                                    <div>
                                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-700 hover:bg-green-600 text-white rounded-md font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                            Daftar
                                        </button>
                                    </div>
                                    
                                    {{-- UBAH LINK KE LOGIN --}}
                                    <p class="text-center text-sm text-white/80 pt-2">
                                        Sudah punya Akun?
                                        <a href="{{ route('login') }}" class="underline text-white font-semibold">Login di sini</a>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>