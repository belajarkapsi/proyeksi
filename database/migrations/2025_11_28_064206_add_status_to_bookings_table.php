<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBookings extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // kalau sudah ada kolom serupa, sesuaikan
            $table->enum('status', ['pending','confirmed','canceled','completed'])
                  ->default('pending')
                  ->after('id');
            $table->timestamp('canceled_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['status', 'canceled_at']);
        });
    }
}
