<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kamar extends Model
{
    use HasFactory;
    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    protected $fillable = ['id_cabang', 'no_kamar', 'tipe_kamar', 'harga_kamar', 'deskripsi', 'status', 'slug'];

    // Tambahkan Eager Loading
    
    
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }
}
