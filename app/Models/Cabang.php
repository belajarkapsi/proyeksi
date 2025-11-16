<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabang';
    protected $primaryKey = 'id_cabang';
    public $timestamps = true;
    
    protected $fillable = [
        'nama_cabang',
        'deskripsi',
        'jumlah_kamar',
        'lokasi',
        'kategori_cabang',
        'gambar_cabang'
    ];
}
