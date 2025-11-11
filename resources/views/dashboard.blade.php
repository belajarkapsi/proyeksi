<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Pondok Siti Hajar</title>
    <!-- Anda bisa menambahkan link ke Tailwind CSS di sini jika perlu -->
</head>
<body>

    <h1>Selamat Datang di Dashboard, {{ Auth::user()->name }}!</h1>
    <p>Anda telah berhasil login.</p>
    
    <br>

    <!-- Form untuk logout (Praktik terbaik adalah menggunakan form POST untuk logout) -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>