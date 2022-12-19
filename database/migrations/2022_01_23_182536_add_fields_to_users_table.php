<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('NID')->after('name')->nullable();
            $table->float('salary')->after('password')->nullable();
            $table->unsignedBigInteger('manager_id')->after('salary')->nullable();
            $table->foreign('manager_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->dateTime('start_date')->after('manager_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
