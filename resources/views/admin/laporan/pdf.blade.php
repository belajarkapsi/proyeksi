<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Terpadu SIPESTAR - {{ $periode }}</title>
    <style>
        @page { size: a4 portrait; margin: 1cm; }
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.2; font-size: 10px; margin: 0; padding: 0; }
        .header-container { border-bottom: 2px solid #16a34a; padding-bottom: 10px; margin-bottom: 20px; position: relative; }
        .logo-text { font-size: 24px; font-weight: bold; color: #15803d; margin: 0; }
        .system-name { color: #64748b; font-size: 9px; margin: 0; text-transform: uppercase; }
        .info-section { position: absolute; top: 0; right: 0; text-align: right; }
        .section-title { background: #f0fdf4; padding: 7px 10px; font-weight: bold; margin-top: 15px; border-left: 4px solid #16a34a; font-size: 11px; color: #166534; text-transform: uppercase; }
        
        .summary-table { width: 100%; margin-top: 10px; border-spacing: 5px 0; border-collapse: separate; }
        .summary-item { width: 33.33%; padding: 12px 10px; background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 6px; }
        .summary-label { font-size: 8px; color: #16a34a; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 4px; }
        .summary-value { font-size: 12px; font-weight: bold; color: #14532d; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background: #f8fafc; color: #334155; padding: 8px 6px; text-align: left; font-size: 9px; border-bottom: 1px solid #e2e8f0; text-transform: uppercase; }
        .data-table td { padding: 8px 6px; font-size: 9px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        
        .badge-success { background: #dcfce7; color: #166534; padding: 2px 6px; border-radius: 3px; font-weight: bold; border: 1px solid #bbf7d0; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 40px; text-align: right; font-size: 8px; color: #94a3b8; border-top: 1px solid #f1f5f9; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="header-container">
        <div>
            <div class="logo-text">SIPESTAR</div>
            <div class="system-name">Sistem Pengelolaan Kost & Penginapan Terpadu</div>
        </div>
        <div class="info-section">
            <h3 style="margin:0; color: #14532d;">LAPORAN OPERASIONAL</h3>
            <p style="margin:3px 0; color: #64748b;">Cabang: <strong>{{ $namaCabang }}</strong></p>
            <p style="margin:3px 0; color: #64748b;">Periode: <strong>{{ $periode }}</strong></p>
        </div>
    </div>

    @php
        // Filter transaksi hanya yang berstatus Lunas
        $transaksiLunas = $transaksi->where('status', 'Lunas');
        $totalLunas = $transaksiLunas->sum('total_harga');
        $jumlahLunas = $transaksiLunas->count();
    @endphp

    <div class="section-title">I. Ringkasan Keuangan (Hanya Status Lunas)</div>
    <table class="summary-table">
        <tr>
            <td class="summary-item">
                <span class="summary-label">Total Pendapatan</span>
                <span class="summary-value">Rp {{ number_format($totalLunas, 0, ',', '.') }}</span>
            </td>
            <td class="summary-item">
                <span class="summary-label">Transaksi Lunas</span>
                <span class="summary-value">{{ $jumlahLunas }} Trx</span>
            </td>
            <td class="summary-item">
                <span class="summary-label">Rata-rata Pendapatan</span>
                <span class="summary-value">Rp {{ number_format($jumlahLunas > 0 ? $totalLunas / $jumlahLunas : 0, 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <div class="section-title">II. Okupansi Hunian Saat Ini</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="40%">Nama Cabang</th>
                <th class="text-center">Kapasitas</th>
                <th class="text-center">Terisi</th>
                <th class="text-right">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hunianPerCabang as $h)
            <tr>
                <td style="font-weight: bold; color: #15803d;">{{ $h->nama_cabang }}</td>
                <td class="text-center">{{ $h->total_kamar }}</td>
                <td class="text-center">{{ $h->dihuni }}</td>
                <td class="text-right"><strong>{{ $h->total_kamar > 0 ? round(($h->dihuni / $h->total_kamar) * 100, 1) : 0 }}%</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">III. Rincian Transaksi (Status: Lunas)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="18%">Waktu</th>
                <th width="32%">ID / Cabang</th>
                <th class="text-right" width="30%">Nominal</th>
                <th class="text-center" width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiLunas as $t)
            <tr>
                <td style="color: #64748b;">{{ \Carbon\Carbon::parse($t->waktu_pemesanan)->format('d/m/y H:i') }}</td>
                <td><strong>#{{ $t->id_pemesanan }}</strong><br><small>{{ $t->cabang->nama_cabang ?? '-' }}</small></td>
                <td class="text-right"><strong>{{ number_format($t->total_harga, 0, ',', '.') }}</strong></td>
                <td class="text-center"><span class="badge-success">LUNAS</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center" style="padding: 15px; color: #94a3b8;">Tidak ada transaksi dengan status "Lunas" pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- Detail Service - Hanya yang induk pemesanannya berstatus Lunas --}}
    @php 
        $paidServices = $detailServices->filter(fn($svc) => ($svc->pemesanan->status ?? '') === 'Lunas');
    @endphp

    @if($paidServices->count() > 0)
    <div class="section-title">IV. Rincian Layanan (Khusus Transaksi Lunas)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="15%">ID Order</th>
                <th width="45%">Layanan</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-right" width="30%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paidServices as $svc)
            <tr>
                <td>#{{ $svc->id_pemesanan }}</td>
                <td>
                    <strong>{{ $svc->service->name ?? 'Service' }}</strong><br>
                    <small>Penyewa: {{ $svc->pemesanan->penyewa->nama_lengkap ?? '-' }}</small>
                </td>
                <td class="text-center">{{ $svc->quantity }}</td>
                <td class="text-right" style="font-weight: bold; color: #166534;">Rp {{ number_format($svc->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">Diterbitkan oleh SIPESTAR secara otomatis pada {{ now()->format('d/m/Y H:i') }}</div>
</body>
</html>