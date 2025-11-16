<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
</body>

</html>