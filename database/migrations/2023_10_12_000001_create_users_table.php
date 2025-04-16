<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('names');
            $table->string('lastnames');
            $table->date('dateBirth');
            $table->unsignedBigInteger('areaId');
            $table->integer('type');
            $table->string('CURP')->unique()->nullable();
            $table->string('IMSS')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();

            $table->foreign('areaId')->references('id')->on('areas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
