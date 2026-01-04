<?php

namespace App\Services;

use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Kamar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BookingService
{
    /**
     * Batalkan pemesanan dengan aman:
     **/
    public function cancelBooking(string $idPemesanan, ?string $alasan = null)
    {
        return DB::transaction(function () use ($idPemesanan, $alasan) {
            // Lock baris pemesanan
            $pemesanan = Pemesanan::lockForUpdate()->where('id_pemesanan', $idPemesanan)->firstOrFail();

            // Jika sudah Dibatalkan, nothing to do
            if (in_array(strtolower($pemesanan->status), ['dibatalkan', 'cancelled'])) {
                return $pemesanan;
            }

            // Update status dan timestamp jika kolom tersedia
            $pemesanan->update([
                'status'       => 'Dibatalkan',
                'cancelled_at' => now(),
                'alasan_batal' => $alasan
            ]);

            $items = $pemesanan->items;

            foreach ($items as $item) {
                // Pastikan item punya ID Kamar
                if (!$item->id_kamar) continue;

                // Lock baris kamar
                $kamar = Kamar::where('id_kamar', $item->id_kamar)->lockForUpdate()->first();

                if ($kamar) {
                    // Logika: Hanya ubah jadi 'Tersedia' jika statusnya 'Dihuni'
                    if ($kamar->status === 'Dihuni' || $kamar->status === 'Booked') {
                        $kamar->update(['status' => 'Tersedia']);
                    }
                }
            }

            return $pemesanan;
        });
    }
}
