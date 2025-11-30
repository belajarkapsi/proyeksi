<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pemilik extends Authenticatable
{
    use HasFactory, Notifiable;

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

    protected function casts()
    {
        return [
            'password' => 'hashed',
        ];
    }

}
