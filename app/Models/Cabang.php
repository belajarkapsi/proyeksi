<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cabang extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'cabang';
    protected $primaryKey = 'id_cabang';
    protected $fillable = [
        'nama_cabang',
        'deskripsi',
        'jumlah_kamar',
        'lokasi',
        'kategori_cabang',
        'gambar_cabang'
    ];
}
