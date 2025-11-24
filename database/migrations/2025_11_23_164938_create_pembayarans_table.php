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
                table:'penyewa',
                column: 'id_penyewa'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->string('id_pemesanan', 20);
            $table->integer('total_pembayaran');
            $table->string('kode_transaksi', 100);
            $table->date('waktu_pembayaran');
            $table->string('bukti_pembayaran', 255);
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')->on('pemesanan')
                ->onDelete('cascade');
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
