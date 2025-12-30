<?php

namespace App\Policies;

use App\Models\Service;
use App\Models\Pengelola;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\Response;

class ServicePolicy
{
    /**
     * Admin selalu bisa segalanya (bypass)
     */
    public function before($user, string $ability)
    {
        // Asumsi user admin memiliki properti role atau guard admin
        if ($user->role === 'admin' || $user instanceof \App\Models\Pemilik) {
            return true;
        }

        return null;
    }

    /**
     * Helper privat untuk cek apakah cabang pengelola adalah Villa
     */
    private function isVilla(Pengelola $pengelola): bool
    {
        // Pastikan relasi cabang terload
        if (!$pengelola->cabang) {
            return false;
        }

        return Str::lower($pengelola->cabang->kategori_cabang) === 'villa';
    }

    /**
     * View: Hanya boleh lihat service milik cabangnya sendiri
     * DAN cabangnya harus bertipe VILLA
     */
    public function view(Pengelola $pengelola, Service $service): bool
    {
        return $this->isVilla($pengelola) && ($pengelola->id_cabang === $service->id_cabang);
    }

    /**
     * Create: Hanya boleh buat jika pengelola adalah pengelola VILLA
     */
    public function create(Pengelola $pengelola): bool
    {
        return $this->isVilla($pengelola);
    }

    /**
     * Update: Milik sendiri DAN tipe Villa
     */
    public function update(Pengelola $pengelola, Service $service): bool
    {
        return $this->isVilla($pengelola) && ($pengelola->id_cabang === $service->id_cabang);
    }

    /**
     * Delete: Milik sendiri DAN tipe Villa
     */
    public function delete(Pengelola $pengelola, Service $service): bool
    {
        return $this->isVilla($pengelola) && ($pengelola->id_cabang === $service->id_cabang);
    }
}
