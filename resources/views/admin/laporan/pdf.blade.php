<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Terpadu SIPESTAR - {{ $periode }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 1cm;
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #333; 
            line-height: 1.2; 
            margin: 0;
            padding: 0;
            font-size: 10px;
        }
        .header-container {
            border-bottom: 2px solid #16a34a;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-section {
            float: left;
            width: 60%;
        }
        .info-section {
            float: right;
            width: 40%;
            text-align: right;
        }
        .logo-text {
            font-size: 22px;
            font-weight: bold;
            color: #15803d;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .system-name {
            color: #64748b;
            font-size: 9px;
            margin: 0;
            text-transform: uppercase;
        }
        .clearfix { clear: both; }

        .section-title { 
            background: #f0fdf4; 
            padding: 7px 10px; 
            font-weight: bold; 
            margin-top: 15px; 
            border-left: 4px solid #16a34a; 
            font-size: 11px;
            color: #166534;
            text-transform: uppercase;
        }

        /* Perbaikan Layout Ringkasan agar tidak offside */
        .summary-table {
            width: 100%;
            margin-top: 10px;
            border-spacing: 5px 0; /* Memberi jarak antar kotak */
            border-collapse: separate;
        }
        .summary-item { 
            width: 33.33%;
            padding: 12px 10px; 
            background: #f0fdf4; 
            border: 1px solid #dcfce7;
            border-radius: 6px;
            text-align: left;
            vertical-align: top;
        }
        .summary-label {
            font-size: 8px;
            color: #16a34a;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
            margin-bottom: 4px;
        }
        .summary-value {
            font-size: 12px;
            font-weight: bold;
            color: #14532d;
        }

        table.data-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        .data-table th { 
            background: #f8fafc; 
            color: #334155;
            padding: 8px 6px; 
            text-align: left; 
            font-size: 9px; 
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td { 
            padding: 8px 6px; 
            font-size: 9px; 
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .badge-warning { background: #fef9c3; color: #854d0e; border: 1px solid #fef08a; }

        .footer { 
            margin-top: 40px; 
            text-align: right; 
            font-size: 8px; 
            color: #94a3b8;
            border-top: 1px solid #f1f5f9;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header-container">
            <div class="logo-text">SIPESTAR</div>
            <div class="system-name">Sistem Pengelolaan Kost & Penginapan Terpadu</div>
        </div>
        <div class="info-section">
            <h3 style="margin:0; color: #14532d; font-size: 14px;">LAPORAN OPERASIONAL</h3>
            <p style="margin:3px 0; color: #64748b; font-size: 9px;">Periode: <strong>{{ $periode }}</strong></p>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="section-title">I. Ringkasan Keuangan</div>
    <table class="summary-table">
        <tr>
            <td class="summary-item">
                <span class="summary-label">Total Pendapatan</span>
                <span class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
            </td>
            <td class="summary-item">
                <span class="summary-label">Volume Transaksi</span>
                <span class="summary-value">{{ $jumlahTransaksi }} Trx</span>
            </td>
            <td class="summary-item">
                <span class="summary-label">Avg / Transaksi</span>
                <span class="summary-value">Rp {{ number_format($totalPendapatan / max($jumlahTransaksi, 1), 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <div class="section-title">II. Okupansi Per Cabang</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="45%">Nama Cabang</th>
                <th class="text-center" width="15%">Total</th>
                <th class="text-center" width="15%">Terisi</th>
                <th class="text-right" width="25%">Rasio</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hunianPerCabang as $h)
            <tr>
                <td style="font-weight: bold; color: #15803d;">{{ $h->nama_cabang }}</td>
                <td class="text-center">{{ $h->total_kamar }}</td>
                <td class="text-center">{{ $h->dihuni }}</td>
                <td class="text-right">
                    <strong style="color: #16a34a;">
                        {{ $h->total_kamar > 0 ? round(($h->dihuni / $h->total_kamar) * 100, 1) : 0 }}%
                    </strong>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">III. Rincian Transaksi</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="20%">Waktu</th>
                <th width="15%">ID Order</th>
                <th width="25%">Cabang</th>
                <th class="text-right" width="25%">Nominal</th>
                <th class="text-center" width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $t)
            <tr>
                <td style="color: #64748b;">{{ \Carbon\Carbon::parse($t->waktu_pemesanan)->format('d/m/y H:i') }}</td>
                <td><strong>#{{ $t->id_pemesanan }}</strong></td>
                <td>{{ $t->cabang->nama_cabang ?? '-' }}</td>
                <td class="text-right" style="font-weight: bold;">{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                <td class="text-center">
                    <span class="badge {{ in_array(strtolower($t->status), ['lunas', 'paid']) ? 'badge-success' : 'badge-warning' }}">
                        {{ strtoupper($t->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">IV. Rincian Item Kamar Terpesan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">ID Order</th>
                <th width="35%">Penyewa / Kamar</th>
                <th class="text-right" width="25%">Harga Item</th>
                <th class="text-right" width="25%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailItems as $item)
            <tr>
                <td>#{{ $item->id_pemesanan }}</td>
                <td>
                    <strong>{{ $item->pemesanan->penyewa->nama ?? 'Guest' }}</strong><br>
                    <small>Kamar: {{ $item->kamar->nomor_kamar ?? '-' }}</small>
                </td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">V. Rincian Layanan Tambahan (Service)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">ID Order</th>
                <th width="35%">Jenis Layanan</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-right" width="40%">Total Service</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailServices as $svc)
            <tr>
                <td>#{{ $svc->id_pemesanan }}</td>
                <td>
                    <strong>{{ $svc->service->nama_service ?? 'Layanan' }}</strong><br>
                    <small>Ket: {{ $svc->pemesanan->penyewa->nama ?? '-' }}</small>
                </td>
                <td class="text-center">{{ $svc->jumlah }}</td>
                <td class="text-right" style="font-weight: bold; color: #166534;">
                    Rp {{ number_format($svc->subtotal, 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Diterbitkan secara digital oleh <strong>SIPESTAR</strong> pada {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>