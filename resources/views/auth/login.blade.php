<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Pondok Siti Hajar</title>

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
    </style>
</head>

<body class="antialiased bg-gray-900 text-gray-100 overflow-hidden">

    <div class="min-h-screen w-full relative flex items-center justify-center p-4 md:p-6 lg:p-8">

        {{-- BACKGROUND UTAMA (Dipudarkan/Blur) --}}
        <div class="absolute inset-0 w-full h-full overflow-hidden">
            <img src="{{ asset('images/background.jpg') }}" alt="Background"
                 class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none select-none blur-sm scale-105">

            {{-- Overlay Gelap --}}
            <div class="absolute inset-0 bg-black/70"></div>
        </div>

        {{-- Container Grid --}}
        <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">

            <!-- BAGIAN KIRI: Branding & Logo -->
            <div class="relative flex flex-col justify-center items-center lg:items-start text-white fade-in-up min-h-[400px]">

                {{-- LOGO BESAR (90% Terang, Tegak Lurus, Di Belakang Teks) --}}
                <div class="absolute inset-0 flex items-center justify-center lg:justify-start pointer-events-none select-none z-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Besar"
                         class="w-80 h-80 lg:w-[450px] lg:h-[450px] object-contain opacity-90 drop-shadow-2xl">
                </div>

                {{-- Konten Teks --}}
                <div class="relative z-10 text-center lg:text-left w-full">

                    {{-- Badge Kecil --}}
                    <div class="inline-block mb-4 px-4 py-1 bg-black/50 border border-green-400/50 rounded-full text-green-300 text-xs font-bold tracking-widest uppercase backdrop-blur-md shadow-lg">
                        Sistem Informasi Penginapan
                    </div>

                    {{-- Judul Utama dengan Shadow Kuat --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black tracking-tight leading-tight mb-4 drop-shadow-[0_5px_5px_rgba(0,0,0,1)]">
                        Pondok <br>
                        <span class="text-green-400">Siti Hajar</span>
                    </h1>

                    <p class="text-base sm:text-lg text-white font-medium max-w-lg mx-auto lg:mx-0 leading-relaxed drop-shadow-[0_2px_2px_rgba(0,0,0,0.8)]">
                        Nikmati kemudahan pengelolaan hunian dan reservasi dalam satu pintu. Efisien, transparan, dan terpercaya.
                    </p>
                </div>
            </div>

            <!-- BAGIAN KANAN: Form Login -->
            <div class="w-full max-w-md mx-auto fade-in-up delay-100">

                {{-- Card Container: Gradient Hijau --}}
                <div class="bg-linear-to-b from-green-800 to-green-900 border border-green-600/30 rounded-2xl shadow-2xl shadow-black/50 p-8 relative overflow-hidden group">

                    <div class="relative z-10">
                        {{-- Alert Error --}}
                        @if ($errors->any())
                        <div id="alert-error" class="mb-6 bg-red-500/20 border border-red-500/50 text-red-50 p-4 rounded-xl text-sm backdrop-blur-sm animate-pulse shadow-inner">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-300 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif


                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-white tracking-wide">Selamat Datang</h2>
                            <p class="text-green-100 text-sm mt-1 opacity-80">Silakan login untuk melanjutkan.</p>
                        </div>


                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf

                            {{-- Username --}}
                            <div>
                                <label for="username" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Username / Email</label>
                                <div class="relative group/input">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-green-200 group-focus-within/input:text-green-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus placeholder="Masukkan username"
                                        class="w-full pl-10 pr-4 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">
                                </div>
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-xs font-bold text-green-100 uppercase tracking-wider mb-2 ml-1">Password</label>
                                <div class="relative group/input">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-green-200 group-focus-within/input:text-green-600 transition-colors" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" name="password" type="password" required placeholder="Masukkan password"
                                        class="w-full pl-10 pr-12 py-3 bg-white/10 border border-green-600/30 rounded-xl text-white placeholder-green-200/50 focus:outline-none focus:ring-2 focus:ring-green-400 focus:bg-white focus:text-gray-900 transition-all duration-300 shadow-inner">

                                    {{-- Toggle Icon --}}
                                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-green-200 hover:text-white cursor-pointer focus:outline-none transition-colors">
                                        <svg id="eye-open" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="eye-closed" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            {{-- Remember & Forgot --}}
                            <div class="flex items-center justify-between text-sm">
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" name="remember" id="remember_me" class="rounded border-gray-500 bg-black/20 text-green-500 focus:ring-green-400 cursor-pointer w-4 h-4">
                                    <span class="ml-2 text-green-100 group-hover:text-white transition-colors">Ingat Saya</span>
                                </label>
                                <a href="#" class="text-green-200 hover:text-white hover:underline transition-colors font-medium">Lupa Password?</a>
                            </div>

                            {{-- Submit Button: BACKGROUND HIJAU & TEKS PUTIH --}}
                            <button type="submit" class="w-full py-3.5 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl shadow-lg hover:shadow-green-500/50 hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-green-400/50 transition-all duration-300 transform">
                                MASUK SEKARANG
                            </button>

                            {{-- Register --}}
                            <div class="text-center border-t border-green-700 pt-4">
                                <p class="text-sm text-green-100/70">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}" class="text-white hover:text-green-300 font-bold transition-colors ml-1">Daftar Akun</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Card --}}

            </div>
        </div>

        {{-- Footer --}}
        <div class="absolute bottom-4 w-full text-center text-white/40 text-xs tracking-wider font-light">
            &copy; {{ date('Y') }} Pondok Siti Hajar System.
        </div>
    </div>

{{-- JAVASCRIPT (Vanilla - Tetap Logika Lama) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. Toggle Password Logic
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();

                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');

                if (isPassword) {
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    eyeOpen.classList.remove('hidden');
                    eyeClosed.classList.add('hidden');
                }
            });
        }
    });
</script>

{{-- SweetAlert (Logika Lama) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'warning',
            title: 'Akses Ditolak',
            text: '{{ session('error') }}',
            confirmButtonColor: '#166534',
            confirmButtonText: 'Siap, Login Dulu'
        });
    @endif

    @if(session('status'))
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '{{ session('status') }}',
            confirmButtonColor: '#166534',
            timer: 3000,
            timerProgressBar: true
        });
    @endif
</script>

@include('sweetalert::alert')

</body>
</html>
