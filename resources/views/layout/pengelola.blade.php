<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - Pondok Siti Hajar</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body class="bg-gray-100">

    {{-- Navbar --}}
    <x-navbar-pengelola></x-navbar-pengelola>

    {{-- Welcome Banner --}}
    <header class="bg-green-600 shadow-inner">
        @yield('header')
    </header>

    {{-- Konten Utama Halaman --}}
    <main>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    @include('sweetalert::alert')
    @stack('scripts')
</body>
</html>
