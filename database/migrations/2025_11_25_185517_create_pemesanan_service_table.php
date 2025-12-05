<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemesananServiceTable extends Migration
{
    public function up()
    {
        Schema::create('pemesanan_service', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel pemesanan (pastikan tabel pemesanan punya id bigInt)
            $table->string('id_pemesanan', 20);

            $table->unsignedBigInteger('id_service');

            $table->integer('quantity')->default(1);
            $table->bigInteger('price');
            $table->bigInteger('subtotal');
            $table->timestamps();

            $table->foreign('id_pemesanan')
                ->references('id_pemesanan')->on('pemesanan')
                ->onDelete('cascade');

            $table->foreign('id_service')
                ->references('id')->on('services')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanan_service');
    }
}
