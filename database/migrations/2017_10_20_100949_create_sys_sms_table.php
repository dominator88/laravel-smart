<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_sms', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type' , ['captcha']);
            $table->string('phone');
            $table->string('content');
            $table->integer('temp_id');
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->string('message_id' , 32)->nullable();
            $table->tinyInteger('status' )->default(1);

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
        Schema::dropIfExists('sys_sms');
    }
}
