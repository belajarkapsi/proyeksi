<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Crypt;
use Throwable;

trait Encryptable
{
    // Enkripsi (Mutator)
    public static function bootEncryptable()
    {
        static::saving(function ($model) {
            $columns = $model->encryptedColumns ?? []; // Ambil daftar kolom

            foreach ($columns as $column) {
                // Cek apakah kolomnya ada isinya dan baru saja diubah
                if (!empty($model->$column) && $model->isDirty($column)) {
                    try {
                        $model->$column = Crypt::encryptString($model->$column);
                    } catch (Exception $e) {
                        // Kalau gagal, tidak dienkripsi
                    }
                }
            }
        });
    }

    // Dekripsi (Accessor)
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key); // Ambil nilai asli
        $columns = $this->encryptedColumns ?? []; // Ambil daftar kolom

        if (in_array($key, $columns)) { // cek apakah kolom ini termasuk yang dienkripsi
            if (!is_null($value) && $value !== '') {
                try {
                    return Crypt::decryptString($value);
                } catch (Throwable $e) {
                    return $value; // Kembalikan data aslinya jika gagal
                }
            }
        }

        return $value;
    }

    // Memastikan data terdekripsi saat diubah jadi Array/JSON
    public function toArray()
    {
        $array = parent::toArray();
        $columns = $this->encryptedColumns ?? [];

        foreach ($columns as $column) {
            if (isset($array[$column])) {
                try {
                    $array[$column] = Crypt::decryptString($this->attributes[$column]);
                } catch (Throwable $e) {
                    //
                }
            }
        }

        return $array;
    }
}
