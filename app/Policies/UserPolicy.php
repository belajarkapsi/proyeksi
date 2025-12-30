<?php

namespace App\Policies;

use App\Models\Pemesanan;
use App\Models\Pemilik;
use App\Models\Pengelola;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function before($user, $abilty)
    {
        if($user instanceof Pemilik || $user->role === 'admin') {
            return true;
        }
    }

    private function hasTransactionWith(Pengelola $pengelola, User $penyewa): bool {
        // cek apakah ada penyewa yang memesan di cabang milik pengelola
        return Pemesanan::where('id_penyewa', $penyewa->id_penyewa)
            ->where('id_cabang', $pengelola->id_cabang)
            ->exists();
    }

    /**
     * Menentukan apakah pengelola bisa melihat data.
     */
    public function view(Pengelola $pengelola, User $penyewa): bool
    {
        return $this->hasTransactionWith($pengelola, $penyewa);
    }

    /**
     * Menentukan apakah pengelola bisa merubah data.
     */
    public function update(Pengelola $pengelola, User $penyewa): bool
    {
        return $this->hasTransactionWith($pengelola, $penyewa);
    }

    /**
     * Menentukan apakah pengelola bisa menghapus data.
     */
    public function delete(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

}
