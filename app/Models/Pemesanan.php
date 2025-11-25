<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    protected $casts = [
        'waktu_pemesanan' => 'datetime',
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
}
