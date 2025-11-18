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
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id('id_pemesanan');
            $table->foreignId('id_penyewa')->constrained(
                table:'users',
                column: 'id_penyewa'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_cabang')->constrained(
                table:'cabang',
                column: 'id_cabang'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_kamar')->constrained(
                table:'kamar',
                column: 'id_kamar'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah_pemesanan');
            $table->integer('harga');
            $table->date('waktu_pemesanan');
            $table->date('waktu_checkin');
            $table->date('waktu_checkout');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};
