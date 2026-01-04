<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use Encryptable;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_penyewa',
        'id_pemesanan',
        'total_pembayaran',
        'kode_transaksi',
        'waktu_pembayaran',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'waktu_pembayaran' => 'date',
    ];

    protected $encryptedColumns = [
        'kode_transaksi',
        'bukti_pembayaran'
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
