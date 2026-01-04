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
        Schema::create('cabang', function (Blueprint $table) {
            $table->id('id_cabang');
            $table->string('nama_cabang', 255);
            $table->text('deskripsi');
            $table->integer('jumlah_kamar');
            $table->text('lokasi');
            $table->enum('kategori_cabang', ['kost', 'villa']);
            $table->string('gambar_cabang', 255)->nullable();
            $table->unsignedBigInteger('id_pemilik');
            $table->timestamps();

            $table->foreign('id_pemilik')
                ->references('id_pemilik')
                ->on('pemilik')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cabang');
    }
};
