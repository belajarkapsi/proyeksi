<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Pemilik extends Authenticatable
{
    use HasFactory, Notifiable;
    use Encryptable;

    protected $table = 'pemilik';
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'username',
        'password',
        'no_telp',
        'foto_profil',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $encryptedColumns = [
        'nama_lengkap',
        'no_telp'
    ];

    protected function casts()
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function cabangs(): HasMany
    {
        return $this->hasMany(Cabang::class, 'id_pemilik', 'id_pemilik');
    }

}
