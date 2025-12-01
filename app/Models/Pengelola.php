<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengelola extends Model
{
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
        'role'
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
}
