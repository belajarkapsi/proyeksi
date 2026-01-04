<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\Encryptable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;
use Throwable;

use function Laravel\Prompts\error;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use Encryptable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'penyewa';
    protected $primaryKey = 'id_penyewa';
    protected $fillable = [
        'nama_lengkap',
        'no_telp',
        'email',
        'username',
        'password',
        'tanggal_lahir',
        'usia',
        'asal',
        'jenis_kelamin',
        'alamat',
        'foto_profil'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $encryptedColumns = [
        'nama_lengkap',
        'no_telp',
        'tanggal_lahir',
        'asal',
        'alamat',
    ];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'id_penyewa', 'id_penyewa');
    }
}
