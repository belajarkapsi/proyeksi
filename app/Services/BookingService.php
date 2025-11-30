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
     * - lock row pemesanan
     * - set status = 'Dibatalkan' dan cancelled_at jika kolom tersedia
     * - release flag pada tabel kamar (is_reserved/reserved_until) jika tidak ada booking aktif lain yang menempati rentang
     *
     * @param string $idPemesanan
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \Exception
     */
    public static function cancelBooking(string $idPemesanan)
    {
        return DB::transaction(function () use ($idPemesanan) {
            // Lock baris pemesanan
            $pemesanan = Pemesanan::lockForUpdate()->where('id_pemesanan', $idPemesanan)->firstOrFail();

            // Jika sudah Dibatalkan, nothing to do
            if (strtolower($pemesanan->status) === strtolower('Dibatalkan') || strtolower($pemesanan->status) === strtolower('Canceled')) {
                return $pemesanan;
            }

            // Update status dan timestamp jika kolom tersedia
            $dataUpdate = ['status' => 'Dibatalkan'];
            // Perhatikan: kolom yang kita cek/tulis adalah 'cancelled_at' (dua l) agar konsisten dengan migration/model sebelumnya
            if (Schema::hasColumn((new Pemesanan)->getTable(), 'cancelled_at')) {
                $dataUpdate['cancelled_at'] = Carbon::now();
            }
            $pemesanan->update($dataUpdate);

            // Release room flags jika ada (cek per item)
            $itemTable = (new PemesananItem)->getTable();
            $items = PemesananItem::where('id_pemesanan', $pemesanan->id_pemesanan)->get();

            // Jika tabel kamar punya kolom is_reserved/reserved_until, kita handle release
            $kamarTable = (new Kamar)->getTable();
            $hasIsReserved = Schema::hasColumn($kamarTable, 'is_reserved');
            $hasReservedUntil = Schema::hasColumn($kamarTable, 'reserved_until');

            if ($hasIsReserved || $hasReservedUntil) {
                foreach ($items as $item) {
                    $idKamar = $item->id_kamar ?? ($item->room_id ?? null);
                    if (! $idKamar) continue;

                    // Lock kamar row
                    $kamar = Kamar::where('id_kamar', $idKamar)->lockForUpdate()->first();
                    if (! $kamar) continue;

                    // Periksa apakah masih ada pemesanan aktif lain yang menempati rentang item ini.
                    // Kita anggap pemesanan aktif = status != 'Dibatalkan' (sederhana & aman)
                    // dan juga bukan header pemesanan yang sedang kita batalkan.
                    $overlapExists = DB::table($itemTable . ' as pi')
                        ->join((new Pemesanan)->getTable() . ' as p', 'pi.id_pemesanan', '=', 'p.id_pemesanan')
                        ->where('pi.id_kamar', $idKamar)
                        ->where('p.id_pemesanan', '!=', $pemesanan->id_pemesanan)
                        ->where(function ($q) use ($item) {
                            // kolom waktu_checkin/waktu_checkout umum; fallback kalau kolom berbeda, query bisa diadaptasi.
                            if (isset($item->waktu_checkin) && isset($item->waktu_checkout)) {
                                $q->where('pi.waktu_checkin', '<', $item->waktu_checkout)
                                  ->where('pi.waktu_checkout', '>', $item->waktu_checkin);
                            } else if (isset($item->tanggal_mulai) && isset($item->tanggal_selesai)) {
                                // fallback nama kolom alternatif
                                $q->where('pi.tanggal_mulai', '<', $item->tanggal_selesai)
                                  ->where('pi.tanggal_selesai', '>', $item->tanggal_mulai);
                            } else {
                                // jika tidak menemukan kolom yang cocok, pilih kondisi yang pasti false agar tidak memblok release
                                $q->whereRaw('1 = 0');
                            }
                        })
                        ->whereNotIn('p.status', ['Dibatalkan', 'Canceled']) // exclude canceled
                        ->exists();

                    if (! $overlapExists) {
                        // tidak ada pemesanan aktif lain -> release flag jika ada
                        if ($hasIsReserved) {
                            $kamar->is_reserved = false;
                        }
                        if ($hasReservedUntil) {
                            $kamar->reserved_until = null;
                        }
                        $kamar->save();
                    }
                }
            }

            return $pemesanan;
        });
    }
}
