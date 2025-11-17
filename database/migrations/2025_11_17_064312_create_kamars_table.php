<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->foreignId('id_cabang')->constrained(
                table:'cabang',
                column: 'id_cabang'
            )->onUpdate('cascade')->onDelete('cascade');
            $table->char('no_kamar', length:10);
            $table->enum('tipe_kamar', ['Ekonomis', 'Standar']);
            $table->integer('harga_kamar');
            $table->text('deskripsi');
            $table->enum('status', ['Tersedia', 'Dihuni'])->default('Tersedia');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
