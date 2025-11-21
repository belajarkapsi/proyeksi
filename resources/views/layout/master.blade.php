<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />
</head>
<body class="h-full">
    <div class="min-h-full">

    <!-- Komponen Navbar -->
    <x-navbar></x-navbar>

    <main>
        <div class="content">
            @yield('content')
        </div>
    </main>

    <!-- Komponen Footer -->
    <x-footer></x-footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>

    @stack('scripts')
</body>

</html>
