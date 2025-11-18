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
        Schema::create('pengelola', function (Blueprint $table) {
            $table->id('id_pengelola');
            $table->string('email', 255);
            $table->string('username', 255);
            $table->string('password', 255);
            $table->string('nama_lengkap', 255);
            $table->string('no_telp', 25);
            $table->string('foto_profil', 255);
            $table->char('role', 9);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengelola');
    }
};
