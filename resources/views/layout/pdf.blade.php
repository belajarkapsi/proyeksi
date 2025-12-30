<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans; font-size: 11px; }
        h2 { margin: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; }
        th { background: #eee; }
        .center { text-align: center; }
    </style>
</head>
<body>

<h2 class="center">{{ $judul }}</h2>
<p class="center">{{ $periode }}</p>

@yield('content')

<p style="margin-top:30px;font-size:10px">
    Dicetak pada {{ now()->format('d-m-Y') }}
</p>

</body>
</html>
