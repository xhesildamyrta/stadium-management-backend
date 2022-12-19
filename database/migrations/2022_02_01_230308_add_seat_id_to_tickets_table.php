<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatIdToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('seat_id')->after('event_id');
            $table->foreign('seat_id')->references('id')->on('seats')->onUpdate('cascade')->onDelete('cascade');
            $table->dropColumn(['chair_number', 'zone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->integer('chair_number');
            $table->string('zone');
        });
    }
}
