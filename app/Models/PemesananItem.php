<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananItem extends Model
{
    protected $table = 'pemesanan_item';

    protected $fillable = [
        'id_pemesanan',
        'id_kamar',
        'jumlah_pesan',
        'harga',
        'waktu_checkin',
        'waktu_checkout'
    ];

    protected $casts = [
        'waktu_checkin' => 'date',
        'waktu_checkout' => 'date',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
