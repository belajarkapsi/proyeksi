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
        $table->string('id_pemesanan'); // or reference to header PK
        $table->unsignedBigInteger('id_service');
        $table->integer('quantity')->default(1);
        $table->bigInteger('price');
        $table->bigInteger('subtotal');
        $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemesanan_service');
    }
}
