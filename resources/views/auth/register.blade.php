<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Daftar Akun - Pondok Siti Hajar</title>

    {{-- Font: Roboto --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Roboto', sans-serif; }
        
        /* Animasi Masuk */
        .fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }
        
        .delay-100 { animation-delay: 0.1s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.4); }
    </style>
</head>

<body class="antialiased bg-gray-900 text-gray-100 overflow-x-hidden">

    {{-- BACKGROUND UTAMA --}}
    <div class="fixed inset-0 w-full h-full">
        <img src="{{ asset('images/background.jpg') }}" alt="Background" 
             class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none select-none blur-sm scale-105">
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    {{-- WRAPPER UTAMA --}}
    {{-- 'items-start' penting agar sticky berfungsi dengan baik pada grid --}}
    <div class="relative z-10 w-full min-h-screen flex justify-center">
        
        <div class="w-full max-w-7xl grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start px-4 md:px-8">

            <!-- BAGIAN KIRI: Logo & Deskripsi -->
            <!-- 
               LOGIKA CSS:
               - Mobile: Relative, padding atas bawah (py-12), center text.
               - Desktop (lg): Sticky di top-0, tinggi setara layar (h-screen), content centered vertikal (justify-center).
            -->
            <div class="relative flex flex-col justify-center items-center lg:items-start text-white fade-in-up 
                        w-full py-12 lg:py-0 
                        lg:sticky lg:top-0 lg:h-screen lg:justify-center">
                
                {{-- Logo Watermark Besar (Di Belakang Teks) --}}
                <div class="absolute inset-0 flex items-center justify-center lg:justify-start pointer-events-none select-none z-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Besar" 
                         class="w-72 h-72 sm:w-80 sm:h-80 lg:w-[500px] lg:h-[500px] object-contain opacity-90 drop-shadow-2xl">
                </div>

                {{-- Konten Teks --}}
                <div class="relative z-10 text-center lg:text-left w-full max-w-lg mx-auto lg:mx-0">
                    
                    <div class="inline-block mb-4 px-4 py-1 bg-black/50 border border-green-400/50 rounded-full text-green-300 text-xs font-bold tracking-widest uppercase backdrop-blur-md shadow-lg">
                        Sistem Informasi Penginapan
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-tight mb-6 drop-shadow-[0_5px_5px_rgba(0,0,0,1)]">
                        Pondok <br>
                        <span class="text-green-400">Siti Hajar</span>
                    </h1>

                    <p class="text-base sm:text-lg text-white font-medium leading-relaxed drop-shadow-[0_2px_2px_rgba(0,0,0,0.8)]">
                        Bergabunglah bersama kami. Nikmati kemudahan reservasi dan manajemen hunian yang efisien, transparan, dan terpercaya.
                    </p>
                </div>
            </div>

            <!-- BAGIAN KANAN: Form Registrasi -->
            <!-- Akan scroll secara natural, sementara bagian kiri tetap diam (sticky) -->
            <div class="w-full max-w-lg mx-auto lg:mx-0 lg:ml-auto pb-12 lg:py-12 fade-in-up delay-100">
                
                {{-- Card Container --}}
                <div class="bg-gradient-to-b from-green-800 to-green-900 border border-green-600/30 rounded-2xl shadow-2xl shadow-black/50 p-6 sm:p-8 relative overflow-hidden group">
                    
                    <div class="relative z-10">
                        <div class="mb-6 text-center">
                            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-wide">Buat Akun Baru</h2>
                            <p class="text-green-100 text-sm mt-1 opacity-80">Lengkapi data diri Anda di bawah ini</p>
                        </div>

                        {{-- Alert Error --}}
                        @if ($errors->any())
                            <div class="mb-6 bg-red-500/20 border border-red-500/50 text-red-50 p-4 rounded-xl text-sm backdrop-blur-sm animate-pulse shadow-inner">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf

                            {{-- NAMA LENGKAP --}}
                            <div>
                                <label for="nama_lengkap" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Nama Lengkap</label>
                                <div class="relative group/input">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-200">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    </span>
                                    <input id="nama_lengkap" name="nama_lengkap" type="text" autofocus value="{{ old('nama_lengkap') }}" placeholder="Nama Lengkap Sesuai KTP" 
                                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">
                                </div>
                                @error('nama_lengkap') <p class="text-red-300 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- NOMOR TELEPON --}}
                            <div>
                                <label for="no_telp" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Nomor Telepon</label>
                                <div class="relative group/input">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-200">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                                    </span>
                                    <input id="no_telp" name="no_telp" type="tel" value="{{ old('no_telp') }}" placeholder="Contoh: 08123456789" 
                                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">
                                </div>
                                @error('no_telp') <p class="text-red-300 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- EMAIL --}}
                            <div>
                                <label for="email" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Email</label>
                                <div class="relative group/input">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-200">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" /></svg>
                                    </span>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan Alamat Email" 
                                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">
                                </div>
                                @error('email') <p class="text-red-300 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- USERNAME --}}
                            <div>
                                <label for="username" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Username</label>
                                <div class="relative group/input">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-200">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                    </span>
                                    <input id="username" name="username" type="text" value="{{ old('username') }}" placeholder="Buat Username Unik" 
                                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">
                                </div>
                                @error('username') <p class="text-red-300 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- PASSWORD --}}
                            <div>
                                <label for="password" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Password</label>
                                <div class="relative group/input">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-200">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/></svg>
                                    </span>
                                    <input id="password" name="password" type="password" placeholder="Minimal 8 Karakter" 
                                        class="w-full pl-10 pr-12 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">

                                    {{-- Toggle Icon --}}
                                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-green-200 hover:text-white cursor-pointer focus:outline-none transition-colors">
                                        <svg id="eye-open" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg id="eye-closed" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password') <p class="text-red-300 text-xs mt-1 ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- CONFIRM PASSWORD --}}
                            <div>
                                <label for="password_confirmation" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Konfirmasi Password</label>
                                <div class="relative group/input">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-green-200">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/></svg>
                                    </span>
                                    <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi Password" 
                                        class="w-full pl-10 pr-12 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">

                                    {{-- Toggle Icon --}}
                                    <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center text-green-200 hover:text-white cursor-pointer focus:outline-none transition-colors">
                                        <svg id="eye-open-confirm" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <svg id="eye-closed-confirm" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- TOMBOL SUBMIT (HIJAU + TEXT PUTIH) --}}
                            <div class="pt-2">
                                <button type="submit" class="w-full py-3.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl shadow-lg hover:shadow-green-500/50 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-green-400/50 transition-all duration-300 transform">
                                    DAFTAR SEKARANG
                                </button>
                            </div>

                            {{-- LINK LOGIN --}}
                            <div class="text-center border-t border-green-700 pt-4">
                                <p class="text-sm text-green-100/70">
                                    Sudah punya Akun? 
                                    <a href="{{ route('login') }}" class="text-white hover:text-green-300 font-bold transition-colors ml-1">Login di sini</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Card --}}
            </div>
        </div>
    </div>

{{-- JAVASCRIPT (Logika Lama) --}}
<script>
    function setupPasswordToggle(toggleId, inputId, openIconId, closedIconId) {
        const toggleBtn = document.getElementById(toggleId);
        const inputField = document.getElementById(inputId);
        const openIcon = document.getElementById(openIconId);
        const closedIcon = document.getElementById(closedIconId);

        if (toggleBtn && inputField && openIcon && closedIcon) {
            toggleBtn.addEventListener('click', function () {
                // 1. Cek tipe input saat ini
                const isPassword = inputField.getAttribute('type') === 'password';

                // 2. Ubah tipe input (text <-> password)
                inputField.setAttribute('type', isPassword ? 'text' : 'password');

                // 3. Tukar visibilitas icon
                if (isPassword) {
                    openIcon.classList.add('hidden');
                    closedIcon.classList.remove('hidden');
                } else {
                    openIcon.classList.remove('hidden');
                    closedIcon.classList.add('hidden');
                }
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Setup Toggle untuk Input Password Utama
        setupPasswordToggle('togglePassword', 'password', 'eye-open', 'eye-closed');

        // Setup Toggle untuk Input Konfirmasi Password
        setupPasswordToggle('togglePasswordConfirmation', 'password_confirmation', 'eye-open-confirm', 'eye-closed-confirm');
    });
</script>

</body>
</html>