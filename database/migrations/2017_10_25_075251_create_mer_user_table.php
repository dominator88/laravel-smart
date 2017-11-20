<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id')->default(0);
            $table->integer('referee_id')->default(0);
            $table->tinyInteger('sex');
            $table->string('username');
            $table->string('nickname');
            $table->string('password');
            $table->string('icon')->nullable();
            $table->string('email')->nullable();
            $table->string('truename')->nullable();
            $table->string('phone');
            $table->decimal('bucks')->default(0);
            $table->integer('points')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->string('industries')->nullable();
            $table->enum('reg_from' , ['phone', 'qq', 'wx', 'wb', 'email', 'unknown']);
            $table->string('reg_ip')->nullable();
            $table->dateTime('reg_at')->nullable();
            $table->string('login_ip')->nullable();
            $table->dateTime('login_at')->nullable();
            $table->tinyInteger('for_test')->default(0);
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
        Schema::dropIfExists('mer_user');
    }
}
