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
            $table->string('id_pemesanan', 20)->primary();
            $table->foreignId('id_penyewa')->constrained(
                table:'penyewa',
                column: 'id_penyewa'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_cabang')->constrained(
                table:'cabang',
                column: 'id_cabang'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('waktu_pemesanan');
            $table->timestamp('expired_at')->nullable();
            $table->integer('total_harga')->default('0');
            $table->enum('status', ['Belum Dibayar', 'Lunas', 'Dibatalkan'])->default('Belum Dibayar');
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
