<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $table = 'pemesanan';
    protected $primaryKey = 'id_pemesanan';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_penyewa',
        'id_cabang',
        'id_kamar',
        'jumlah_pemesanan',
        'harga',
        'waktu_pemesanan',
        'waktu_checkin',
        'waktu_checkout',
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

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }
}
