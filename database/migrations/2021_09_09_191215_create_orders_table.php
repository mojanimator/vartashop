<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->tinyInteger('status'); // 1:in process 2:wait for pay  3:ready for send 4:sent
            $table->string('name', 50);
            $table->string('pay_id', 100)->nullable();
            $table->string('address', 500);
            $table->string('postal_code', 20)->nullable();
            $table->string('phone', 20);
            $table->integer('post_price')->unsigned()->nullable();
            $table->integer('total_price')->unsigned()->nullable();
            $table->string('description', 1024)->nullable();
            $table->string('post_trace', 50)->nullable();
            $table->smallInteger('county_id')->unsigned();
            $table->smallInteger('province_id')->unsigned();
            $table->timestamp('send_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('no action');
            $table->foreign('county_id')->references('id')->on('county')->onDelete('no action');
            $table->foreign('province_id')->references('id')->on('province')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
