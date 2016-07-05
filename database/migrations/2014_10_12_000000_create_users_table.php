<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('uid');
            $table->string('openid',32);
            $table->string('name');
            $table->string('p1')->default(0);
            $table->string('p2')->default(0);
            $table->string('p3')->default(0);
            $table->integer('subtime');
            $table->boolean('state')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
