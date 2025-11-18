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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            $table->foreignId('id_penyewa')->constrained(
                table:'users',
                column: 'id_penyewa'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_pemesanan')->constrained(
                table:'pemesanan',
                column: 'id_pemesanan'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->integer('total_pembayaran');
            $table->string('kode_transaksi', 100);
            $table->date('waktu_pembayaran');
            $table->string('bukti_pembayaran', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
