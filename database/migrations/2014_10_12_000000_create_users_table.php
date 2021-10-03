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
            $table->string('name', 50)->nullable();
            $table->string('username', 30)->unique()->index()->nullable();
            $table->string('telegram_username', 50)->nullable()->unique();
            $table->string('telegram_id', 30)->nullable()->unique();
            $table->string('img', 100)->nullable();
            $table->string('role', 2)->default("us");
            $table->string('password', 255)->nullable();
            $table->string('token')->default(bin2hex(openssl_random_pseudo_bytes(30)));
            $table->integer('score')->default(0);
            $table->string('email', 50)->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->boolean('email_verified')->default(false);
            $table->boolean('phone_verified')->default(false);
            $table->tinyInteger('step')->unsigned()->nullable()->default(0);
            $table->boolean('active')->default(true);
            $table->string('remember_token', 255)->nullable();
            $table->dateTime('expires_at')->nullable()->default(null);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();


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
