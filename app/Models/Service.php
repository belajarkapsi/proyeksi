<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'id_cabang',
        'name',
        'description',
        'price',
    ];

    // Relasi ke Cabang
    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }

    public function pemesananService(): HasMany
    {
        return $this->hasMany(\App\Models\PemesananService::class, 'id', 'id_service');
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Cabang::class, 'id_cabang', 'id_cabang');
    }
}
