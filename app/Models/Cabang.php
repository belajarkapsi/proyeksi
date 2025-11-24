<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

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

    public function kamars(): HasMany
    {
        return $this->hasMany(Kamar::class, 'id_cabang', 'id_cabang');
    }

    public function getRouteParamsAttribute()
{
    return [
        'lokasi'   => Str::slug($this->lokasi),
        'kategori' => Str::slug($this->kategori_cabang)
    ];
}
}
