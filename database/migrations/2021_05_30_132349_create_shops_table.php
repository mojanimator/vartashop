<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->smallInteger('county_id')->nullable()->unsigned();
            $table->smallInteger('province_id')->nullable()->unsigned();
            $table->string('postal_code', 20)->nullable();
            $table->string('address', 500)->nullable();
            $table->string('channel_address', 30)->index()->nullable();//
            $table->string('page_address', 30)->index()->nullable();
            $table->string('site_address', 30)->index()->nullable();
            $table->string('name', 50)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('contact', 30)->nullable();
            $table->tinyInteger('group_id')->unsigned()->nullable();
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();


            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('shops');
    }
}
