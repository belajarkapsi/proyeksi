<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use Encryptable;

    protected $table = 'services';

    protected $fillable = [
        'id_cabang',
        'name',
        'description',
        'price',
        'stock'
    ];

    protected $encryptedColumns = ['description'];

    // Relasi ke Cabang
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    // Perbaikan: parameter ke-2 adalah FK di tabel tujuan, parameter ke-3 adalah PK di tabel ini
    public function pemesananService(): HasMany
    {
        return $this->hasMany(PemesananService::class, 'id_service', 'id');
    }
}
