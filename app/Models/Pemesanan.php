<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pemesanan',
        'id_penyewa',
        'id_cabang',
        'waktu_pemesanan',
        'expired_at',
        'total_harga',
        'status',
        'cancelled_at',
        'alasan_batal',
    ];

    protected $casts = [
        'waktu_pemesanan' => 'datetime',
        'expired_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // relasi
    public function penyewa()
    {
        return $this->belongsTo(User::class, 'id_penyewa', 'id_penyewa');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function items()
    {
        return $this->hasMany(PemesananItem::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function service()
    {
        return $this->hasMany(PemesananService::class, 'id_pemesanan', 'id_pemesanan');
    }

    /**
     * Daftar status yang dianggap "aktif" dan memblokir ketersediaan.
     * Disesuaikan dengan nilai enum di migration: 'Belum Dibayar', 'Lunas', 'Dibatalkan'
     * Kita EXCLUDE 'Dibatalkan' sehingga record batal tidak memblokir pemesanan ulang.
     */
    protected static $activeStatuses = ['Belum Dibayar', 'Lunas'];

    /**
     * Scope Eloquent: hanya booking/pemesanan yang aktif (yang mempengaruhi availability)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', self::$activeStatuses);
    }

    /**
     * Cek apakah sebuah kamar (room) memiliki booking/pemesanan aktif yang overlap
     *
     * Fungsi ini fleksibel: akan mencoba mencari tanggal pada tabel pemesanan terlebih dahulu,
     * jika tidak ditemukan, akan mencoba pada tabel pemesanan_item.
     *
     */
    public static function roomHasConflict($roomId, string $start, string $end, $dbconn = null): bool
    {
        $db = $dbconn ?? DB::connection();
        $schema = $db->getSchemaBuilder();

        // 1) Cek apakah tabel pemesanan (pemesanan) punya kolom tanggal yang bisa dipakai
        $pemesananTable = (new self)->getTable();
        $possiblePemesananCols = [
            ['start' => 'start_date', 'end' => 'end_date'],
            ['start' => 'checkin_date', 'end' => 'checkout_date'],
            ['start' => 'checkin', 'end' => 'checkout'],
            ['start' => 'tanggal_mulai', 'end' => 'tanggal_selesai'],
        ];

        foreach ($possiblePemesananCols as $cols) {
            if ($schema->hasColumn($pemesananTable, $cols['start']) && $schema->hasColumn($pemesananTable, $cols['end'])) {
                // cek overlap langsung pada tabel pemesanan (jika pemesanan menyimpan range)
                return self::active()
                    ->where($cols['start'], '<', $end)
                    ->where($cols['end'], '>', $start)
                    ->exists();
            }
        }

        // 2) Jika pemesanan tidak menyimpan tanggal range, coba pada tabel item (pemesanan_item)
        $itemTable = (new \App\Models\PemesananItem)->getTable();
        $possibleItemCols = [
            ['room_col' => 'room_id', 'start' => 'start_date', 'end' => 'end_date'],
            ['room_col' => 'id_kamar', 'start' => 'tanggal_mulai', 'end' => 'tanggal_selesai'],
            ['room_col' => 'id_kamar', 'start' => 'checkin_date', 'end' => 'checkout_date'],
            ['room_col' => 'room_id', 'start' => 'checkin', 'end' => 'checkout'],
        ];

        foreach ($possibleItemCols as $cols) {
            if ($schema->hasColumn($itemTable, $cols['room_col'])
                && $schema->hasColumn($itemTable, $cols['start'])
                && $schema->hasColumn($itemTable, $cols['end'])) {

                // join antara pemesanan_item dan pemesanan untuk filter status aktif
                $q = DB::table($itemTable . ' as pi')
                    ->join($pemesananTable . ' as p', 'pi.id_pemesanan', '=', 'p.id_pemesanan')
                    ->whereIn('p.status', self::$activeStatuses)
                    ->where('pi.' . $cols['room_col'], '=', $roomId)
                    ->where('pi.' . $cols['start'], '<', $end)
                    ->where('pi.' . $cols['end'], '>', $start);

                return $q->exists();
            }
        }

        // 3) Jika tidak ketemu pola kolom mana pun, default: tidak conflict (kamu perlu menyesuaikan manual)
        return false;
    }
}
