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
            $table->string('name');
            $table->string('lastname');
            $table->date('dateBirth');
            $table->integer('userType')->default(1); // 0 for admin, 1 for user
            $table->tinyInteger('stateBirth');
            $table->string('email')->unique();
            $table->string('password');
            $table->tinyInteger('status')->default(1); // 0 for inactive, 1 for active
            $table->timestamps();

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
