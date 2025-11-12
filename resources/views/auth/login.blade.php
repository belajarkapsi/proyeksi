<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - Pondok Siti Hajar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased text-gray-900">
    
    {{-- Fullscreen background image --}}
    <div class="min-h-screen w-full relative">
        <img src="{{ asset('images/background.jpg') }}" alt="Background" class="absolute inset-0 w-full h-full object-cover object-center pointer-events-none select-none blur-sm">
        
        {{-- Dark overlay supaya teks terbaca --}}
        <div class="absolute inset-0 bg-black/30"></div>
        
        {{-- Centered frosted container --}}
        <div class="relative z-10 min-h-screen flex items-center justify-center p-6">
        
            <div class="w-full max-w-6xl rounded-2xl overflow-hidden bg-white/10 backdrop-blur-md shadow-2xl ring-1 ring-black/10">
                
                <div class="grid grid-cols-1 md:grid-cols-2">
                    
                    <!-- LEFT: Welcome / Logo -->
                    <div class="p-8 md:p-12 flex flex-col justify-center items-center md:items-start text-center md:text-left relative overflow-hidden">
                        <!-- Logo besar tapi tidak mendorong layout -->
                        <div class="absolute top-10 ml-20 md:ml-20 transform -translate-x-1/2 md:translate-x-0 opacity-20 md:opacity-30 scale-150 pointer-events-none select-none">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo Background" class="object-contain w-72 h-72 md:w-96 md:h-96">
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white leading-tight mb-4 relative z-10">
                            Selamat Datang di<br class="hidden md:inline"> Pondok Siti Hajar
                        </h1>
                        <p class="text-sm md:text-base text-white/85 max-w-xl relative z-10">
                            Pondok Siti Hajar merupakan jaringan penginapan yang menyediakan layanan sewa kamar harian dan bulanan bagi masyarakat, mahasiswa, dan wisatawan. Sistem ini mengintegrasikan pemesanan, validasi pembayaran, dan manajemen antar cabang sehingga operasional lebih efisien dan terpusat.
                        </p></div>

                    
                    <!-- RIGHT: Login Card -->
                    <div class="p-6 md:p-12 flex items-center justify-center">
                        <div class="w-full max-w-md">
                        
                        {{-- Card with green translucent bg --}}
                        <div class="bg-green-800/80 border border-white/10 rounded-xl p-6 md:p-8 shadow-xl">
                            <h2 class="text-2xl md:text-3xl font-bold text-white text-center mb-4">Login</h2>
                            
                            {{-- Session / Validation --}}
                            @if (session('status'))
                            <div class="mb-4 px-4 py-3 rounded bg-white/20 text-white text-sm">
                                {{ session('status') }}
                            </div>
                            @endif
                            
                            @if ($errors->any())
                            <div class="mb-4 px-4 py-3 rounded bg-red-600/20 text-red-100 text-sm">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                
                                @csrf
                                
                                {{-- Email --}}
                                <div class="mb-4">
                                    <label for="username" class="block font-medium text-sm text-gray-200 mb-1">Username</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-6 h-6 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-width="2" d="M7 17v1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-4a3 3 0 0 0-3 3Zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                        </span>
                                        
                                        <!-- Input -->
                                        <input id="username" name="username" type="text" required autofocus value="{{ old('username') }}" placeholder="Username / Email" class="w-full px-10 py-3 plrounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                    </div>
                                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                                </div>


                                {{-- Password --}}
                                <div class="mb-4">
                                    <label for="password" class="block font-medium text-sm text-gray-200 mb-1">Password</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z"/>
                                            </svg>
                                        </span>
                                        
                                        <!-- Input -->
                                        <input id="password" name="password" type="password" required autofocus placeholder="Password" class="w-full px-10 py-3 plrounded-md bg-white/90 text-gray-800 placeholder-gray-400 border border-transparent focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                    </div>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                
                                {{-- Remember + Forgot --}}
                                <div class="flex items-center justify-between">
                                    <label class="inline-flex items-center text-sm text-white/90">
                                        <input type="checkbox" name="remember" id="remember_me" class="h-4 w-4 rounded text-green-500 focus:ring-green-400">
                                        <span class="ml-2">Ingat Saya</span>
                                    </label>
                                    <a href="" class="text-sm text-white/90 hover:underline">Lupa Password?</a>
                                </div>
                                
                                {{-- Submit --}}
                                <div>
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-linear-to-b from-green-700 to-green-800 hover:from-green-600 hover:to-green-700 text-white rounded-md font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-green-300 transition">
                                        Login
                                    </button>
                                </div>
                                
                                {{-- Register link --}}
                                <p class="text-center text-sm text-white/80 pt-2">
                                    Tidak punya Akun?
                                    <a href="{{ route('register') }}" class="underline text-white">Buat Akun</a>
                                </p>
                            </form>
                        </div>
                        </div>
                    </div><!-- end right -->
                </div><!-- end grid -->
            </div><!-- end frosted container -->
        </div><!-- end center -->
    </div><!-- end background -->

{{-- Minimal JS untuk toggle password (Tailwind tidak butuh JS) --}}
<script>
    (function(){
        const toggle = document.getElementById('togglePassword');
        const pwd = document.getElementById('password');
        
        if (toggle && pwd) {
            toggle.addEventListener('click', function () {
                const type = pwd.getAttribute('type') === 'password' ? 'text' : 'password';
                pwd.setAttribute('type', type);
                const icon = toggle.querySelector('i');
                if (icon) icon.classList.toggle('fa-eye-slash');
                if (icon) icon.classList.toggle('fa-eye');
            });
        }
    })();
</script>
</body>
</html>
