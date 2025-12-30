<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pemilik', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->string('nama_lengkap', 255);
            $table->string('email', 255)->unique();
            $table->string('username', 255)->unique();
            $table->string('password', 255);
            $table->string('no_telp', 25);
            $table->string('foto_profil', 255)->nullable();
            $table->string('role', 20)->default('admin');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilik');
    }
};
