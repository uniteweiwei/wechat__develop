<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('oid');
            $table->string('ordsn');
            $table->integer('uid');
            $table->string('openid');
            $table->string('xm');
            $table->string('address');
            $table->string('tel');
            $table->float('money',7,2);
            $table->boolean('ispay');
            $table->integer('ordtime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
