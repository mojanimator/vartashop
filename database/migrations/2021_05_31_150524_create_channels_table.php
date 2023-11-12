<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->integer('shop_id')->unsigned();
            $table->string('chat_id', 30)->index();
            $table->string('chat_username', 50)->nullable();;
            $table->string('tag', 100)->nullable();
            $table->boolean('auto_tag')->default(false);
            $table->boolean('auto_msg_day')->default(false);
            $table->boolean('auto_msg_night')->default(false);
            $table->boolean('auto_fun')->default(false);
            $table->boolean('active')->default(true);


            $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
