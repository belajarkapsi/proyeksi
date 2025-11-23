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
        Schema::create('pemesanan_item', function (Blueprint $table) {
            $table->id();
            $table->string('id_pemesanan', 20);
            $table->foreignId('id_kamar')->constrained(
                table:'kamar',
                column: 'id_kamar'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->integer('jumlah_pesan')->default(1);
            $table->integer('harga');
            $table->date('waktu_checkin');
            $table->date('waktu_checkout');
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')->on('pemesanan')
                ->onDelete('cascade');

            $table->index(['id_kamar', 'waktu_checkin', 'waktu_checkout']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanan_item');
    }
};
