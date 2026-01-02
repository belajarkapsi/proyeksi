<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $pemesanan->id_pemesanan }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; font-size: 12px; }
        .invoice-box { padding: 20px; }
        
        /* HEADER STYLING */
        .header-table { width: 100%; border-bottom: 2px solid #16a34a; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-section { vertical-align: middle; }
        .logo-img { height: 45px; margin-right: 10px; vertical-align: middle; }
        .brand { font-size: 20px; font-weight: bold; color: #16a34a; text-transform: uppercase; vertical-align: middle; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; }
        
        .main-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .main-table th { background: #16a34a; color: white; padding: 8px; text-align: left; }
        .main-table td { padding: 8px; border-bottom: 1px solid #eee; }
        
        .section-title { font-weight: bold; color: #16a34a; margin: 15px 0 5px 0; font-size: 13px; text-transform: uppercase; }
        .total-table { float: right; width: 250px; margin-top: 10px; border-top: 2px solid #16a34a; }
        .total-table td { padding: 5px; font-size: 14px; }
        .footer { clear: both; margin-top: 50px; text-align: center; font-size: 10px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header-table">
            <tr>
                <td align="right" style="vertical-align: middle;">
                    <strong style="font-size: 14px;">INVOICE PEMBAYARAN</strong><br>
                    <small>{{ $pemesanan->cabang->nama_cabang }}</small>
                </td>
            </tr>
        </table>

        <table class="info-table">
            <tr>
                <td width="55%">
                    <strong>Detail Penyewa:</strong><br>
                    Nama: {{ $pemesanan->penyewa->nama_lengkap }}<br>
                    Telp: {{ $pemesanan->penyewa->no_telp }}<br>
                    Alamat: {{ $pemesanan->penyewa->alamat ?? '-' }}
                </td>
                <td width="45%" align="right">
                    <strong>No. Pesanan: #{{ $pemesanan->id_pemesanan }}</strong><br>
                    Tanggal: {{ $pemesanan->waktu_pemesanan->format('d/m/Y') }}<br>
                    Lokasi: {{ $pemesanan->cabang->lokasi }}<br>
                    Status: <strong style="color: #16a34a">TERBAYAR LUNAS</strong>
                </td>
            </tr>
        </table>

        {{-- BAGIAN 1: ITEM KAMAR --}}
        <div class="section-title">Detail Penginapan</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th>Unit Kamar</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th align="right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemesanan->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->kamar->nama_kamar ?? 'Kamar' }}</strong><br>
                        <small>{{ $pemesanan->cabang->nama_cabang }}</small>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($item->waktu_checkin)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->waktu_checkout)->format('d M Y') }}</td>
                    <td align="right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- BAGIAN 2: SERVICES --}}
        @if($pemesanan->service && $pemesanan->service->count() > 0)
        <div class="section-title">Layanan Tambahan</div>
        <table class="main-table">
            <thead>
                <tr>
                    <th>Nama Layanan</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pemesanan->service as $svc)
                <tr>
                    <td>{{ $svc->service->name ?? 'Extra Service' }}</td>
                    <td>{{ $svc->quantity }}</td>
                    <td>Rp {{ number_format($svc->price, 0, ',', '.') }}</td>
                    <td align="right">Rp {{ number_format($svc->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        {{-- TOTAL AKHIR --}}
        <table class="total-table">
            <tr>
                <td><strong>Total Pembayaran</strong></td>
                <td align="right">
                    <strong style="color: #16a34a; font-size: 16px;">
                        Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}
                    </strong>
                </td>
            </tr>
        </table>

        <div class="footer">
            <p>Terima kasih telah memilih <strong>{{ $pemesanan->cabang->nama_cabang }}</strong> sebagai tempat menginap Anda.<br>
            Alamat Cabang: {{ $pemesanan->cabang->lokasi }}</p>
            <p><i>Invoice ini diterbitkan secara otomatis dan sah tanpa tanda tangan.</i></p>
        </div>
    </div>
</body>
</html>