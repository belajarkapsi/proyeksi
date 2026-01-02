<!DOCTYPE html>
<html>
<head>
    <title>Laporan Operasional</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; color: #16a34a; } /* Warna Hijau Branding */
        .header p { margin: 2px 0; font-size: 12px; }

        .meta-info { margin-bottom: 20px; width: 100%; }
        .meta-info td { padding: 4px; }

        .section-title { font-size: 14px; font-weight: bold; background-color: #f3f4f6; padding: 8px; margin-top: 20px; margin-bottom: 10px; border-left: 4px solid #16a34a; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f9fafb; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .summary-box { width: 100%; margin-bottom: 20px; }
        .summary-item { display: inline-block; width: 32%; background: #f0fdf4; border: 1px solid #16a34a; padding: 10px; text-align: center; border-radius: 5px; vertical-align: top; }
        .summary-value { font-size: 16px; font-weight: bold; color: #16a34a; display: block; margin-top: 5px; }

        /* Helper khusus kost */
        .kost-stat-item { margin-bottom: 5px; font-size: 11px; text-align: left; border-bottom: 1px dashed #ccc; padding-bottom: 2px; }
        .kost-stat-label { float: left; color: #555; }
        .kost-stat-val { float: right; font-weight: bold; color: #333; }

        .footer { margin-top: 50px; text-align: right; font-size: 11px; }
        .ttd-area { margin-top: 60px; margin-right: 20px; display: inline-block; text-align: center; }
        .ttd-line { border-bottom: 1px solid #333; width: 150px; display: block; margin-top: 50px; }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('images/logo.png') }}" height="40" style="margin-bottom: 5px;">
        <h1>SIPESTAR</h1>
        <p>Sistem Penginapan Pondok Siti Hajar</p>
        <p>Laporan Operasional & Keuangan</p>
    </div>

    <table class="meta-info">
        <tr>
            <td width="15%"><strong>Cabang</strong></td>
            <td width="2%">:</td>
            <td>{{ $cabang->nama_cabang }} ({{ ucfirst($cabang->kategori_cabang) }})</td>
            <td width="15%"><strong>Dicetak Oleh</strong></td>
            <td width="2%">:</td>
            <td>{{ Auth::user()->nama_lengkap }}</td>
        </tr>
        <tr>
            <td><strong>Periode</strong></td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($tgglMulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tgglSelesai)->format('d M Y') }}</td>
            <td><strong>Tanggal Cetak</strong></td>
            <td>:</td>
            <td>{{ now()->format('d M Y H:i') }}</td>
        </tr>
    </table>

    {{-- RINGKASAN PENDAPATAN --}}
    <div class="section-title">Ringkasan Keuangan</div>
    <div class="summary-box">
        <div class="summary-item">
            <span>Total Pendapatan</span>
            <span class="summary-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>

        @if($isVilla)
            <div class="summary-item">
                <span>Dari Kamar</span>
                <span class="summary-value">Rp {{ number_format($pendapatanKamar, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Dari Service</span>
                <span class="summary-value">Rp {{ number_format($pendapatanService, 0, ',', '.') }}</span>
            </div>
        @else
            {{-- DATA ANALITIK KOST --}}
            <div class="summary-item" style="background: #fff; border: 1px solid #ddd;">
                <span style="display:block; margin-bottom:8px; font-weight:bold; color:#555;">Distribusi Tipe Kamar</span>

                @if(isset($chartTipeLabels) && count($chartTipeLabels) > 0)
                    @foreach($chartTipeLabels as $index => $label)
                    <div class="kost-stat-item">
                        <span class="kost-stat-label">{{ $label }}</span>
                        <span class="kost-stat-val">{{ $chartTipeValues[$index] }}x Sewa</span>
                        <div style="clear:both;"></div>
                    </div>
                    @endforeach
                @else
                    <span style="font-size:10px; color:#999; font-style:italic;">Belum ada data sewa</span>
                @endif
            </div>

            <div class="summary-item" style="border: 1px solid #ddd; background: #fff; color: #888;">
                <span>Total Transaksi</span>
                <span class="summary-value" style="color: #555;">{{ $transaksiList->count() }} Trx</span>
            </div>
        @endif
    </div>

    {{-- TABEL PERFORMA KAMAR --}}
    <div class="section-title">Performa Kamar (Top 10)</div>
    <table>
        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Nomor Kamar</th>
                <th>Tipe Kamar</th>
                <th class="text-center">Terpesan</th>
                <th class="text-right">Total Omzet</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topKamar as $index => $room)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $room->no_kamar }}</td>
                <td>{{ $room->tipe_kamar }}</td>
                <td class="text-center">{{ $room->total_book }}x</td>
                <td class="text-right">Rp {{ number_format($room->omzet, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Belum ada data transaksi kamar.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TABEL SERVICE (KHUSUS VILLA) --}}
    @if($isVilla)
        <div class="section-title">Layanan / Service Terlaris</div>
        <table>
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th>Nama Layanan</th>
                    <th class="text-center">Qty Terjual</th>
                    <th class="text-right">Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topServices as $index => $svc)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $svc->service->name ?? '-' }}</td>
                    <td class="text-center">{{ $svc->total_qty }}</td>
                    <td class="text-right">Rp {{ number_format($svc->total_omzet, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada layanan terjual.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    {{-- TABEL PEMBATALAN (Opsional) --}}
    @if($alasanBatal->isNotEmpty())
        <div class="section-title">Analisa Pembatalan</div>
        <table>
            <thead>
                <tr>
                    <th>Alasan Pembatalan</th>
                    <th class="text-center" width="20%">Jumlah Kasus</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alasanBatal as $reason)
                <tr>
                    <td>{{ $reason->alasan_batal }}</td>
                    <td class="text-center">{{ $reason->total }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- TANDA TANGAN --}}
    <div class="footer">
        <div class="ttd-area">
            <p>Mengetahui,</p>
            <p>Pengelola Cabang</p>
            <span class="ttd-line"></span>
            <p>{{ Auth::user()->nama_lengkap }}</p>
        </div>
    </div>

</body>
</html>
