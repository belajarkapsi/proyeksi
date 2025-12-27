<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananService extends Model
{
    protected $table = 'pemesanan_service';

    protected $fillable = [
        'id_pemesanan',
        'service_id',
        'qty',
        'price_at_booking',
        'created_at',
        'updated_at',
    ];

    // Jika kamu ingin helper relation:
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'service_id', 'id_pemesanan');
    }

    public function pemesanan()
    {
        return $this->belongsTo(\App\Models\Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
