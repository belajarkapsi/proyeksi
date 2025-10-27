<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pare Event</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
</head>

<body class="h-screen w-screen overflow-hidden bg-white flex items-center justify-center">

    <!-- Login Container -->
    <div class="bg-green-600 rounded-2xl shadow-2xl w-full max-w-md p-8 sm:p-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-white mb-2">Login</h2>
            <p class="text-gray-200">Silahkan masukkan data Anda untuk login</p>
        </div>

        <!-- Success Message -->
        @if(session('register_success_msg'))
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 relative">
                {{ session('register_success_msg') }}
                <button type="button" class="absolute top-3 right-3 text-green-600 hover:text-green-800" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('login_error'))
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 relative">
                {{ session('login_error') }}
                <button type="button" class="absolute top-3 right-3 text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
            <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 relative">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="absolute top-3 right-3 text-red-600 hover:text-red-800" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <!-- Login Form -->
        <form action="{{-- route('login.process') --}}" method="post">
            @csrf
            
            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="text-white">Email</label>
                <input 
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email Address"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 transition-colors duration-200"
                >
            </div>

            <!-- Password -->
            <div class="mb-4 relative">
                <label for="password" class="text-white">Password</label>
                <input 
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-400 focus:border-green-400 transition-colors duration-200"
                >
                <button 
                    type="button" 
                    id="togglePassword"
                    class="absolute right-3 top-9 text-gray-400 hover:text-gray-600"
                >
                    <i class="fas fa-eye"></i>
                </button>
            </div>

            <!-- Options -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div class="flex items-center">
                    <input type="checkbox" id="rememberMe" name="remember_me" class="w-4 h-4 text-green-600 border-gray-300 rounded">
                    <label for="rememberMe" class="ml-2 text-sm text-white">Ingat Saya!</label>
                </div>
                <a href="#" class="text-green-100 hover:text-green-900 text-sm font-medium transition">Lupa Password?</a>
            </div>

            <!-- Submit -->
            <button 
                type="submit"
                class="w-full bg-green-900 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-800 transition-all duration-200"
            >
                Log In
            </button>
        </form>

        <!-- Register -->
        <div class="text-center mt-6">
            <p class="text-gray-200 text-sm">
                Tidak punya Akun? 
                <a href="{{-- route('register') --}}" class="text-white hover:text-green-900 font-medium transition">Buat Akun</a>
            </p>
        </div>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
