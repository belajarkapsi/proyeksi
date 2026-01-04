<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengelola extends Authenticatable
{
    use Encryptable;

    protected $table = 'pengelola';
    protected $primaryKey = 'id_pengelola';

    protected $fillable = [
        'nama_lengkap',
        'no_telp',
        'email',
        'username',
        'password',
        'tanggal_lahir',
        'usia',
        'jenis_kelamin',
        'foto_profil',
        'role',
        'id_cabang'
    ];

    protected $encryptedColumns = [
        'nama_lengkap',
        'no_telp',
        'tanggal_lahir',
        'usia'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts()
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'id_cabang', 'id_cabang');
    }
}
