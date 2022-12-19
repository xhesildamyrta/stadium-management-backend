<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropClientIdFromHotelReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotel_reservations', function (Blueprint $table) {
            $table->string('name');
            $table->string('NID');
            $table->string('phone');
            $table->dropForeign(['client_id']);
            $table->dropColumn(['client_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotel_reservations', function (Blueprint $table) {
            $table->integer('client_id');
        });
    }
}
