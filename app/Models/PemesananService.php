<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemesananService extends Model
{
    protected $table = 'pemesanan_service';

    protected $fillable = [
        'id_pemesanan',
        'id_service',
        'quantity',
        'price',
        'subtotal',
        'created_at',
        'updated_at',
    ];

    // Jika kamu ingin helper relation:
    public function service()
    {
        return $this->belongsTo(\App\Models\Service::class, 'id_service', 'id');
    }

    public function pemesanan()
    {
        return $this->belongsTo(\App\Models\Pemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
}
