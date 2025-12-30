<?php

namespace App\Policies;

use App\Models\Kamar;
use App\Models\Pengelola;
use Illuminate\Auth\Access\Response;

class KamarPolicy
{
    public function before($user, string $ability)
    {
        if ($user->role === 'admin' || $user instanceof \App\Models\Pemilik) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Pengelola $pengelola, Kamar $kamar): bool
    {
        return $pengelola->id_cabang === $kamar->id_cabang;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Pengelola $pengelola): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Pengelola $pengelola, Kamar $kamar): bool
    {
        return $pengelola->id_cabang === $kamar->id_cabang;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Pengelola $pengelola, Kamar $kamar): bool
    {
        return $pengelola->id_cabang === $kamar->id_cabang;
    }

}
