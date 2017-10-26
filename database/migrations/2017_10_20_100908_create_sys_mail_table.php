<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysMailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_mail', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('type' , ['captcha' , 'reset_pwd']);
            $table->string('name');
            $table->string('address');
            $table->string('subject');
            $table->text('content');
            $table->string('captcha' , 10);
            $table->tinyInteger('status' )->default(1);
            $table->dateTime('sent_at')->nullable();
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
        Schema::dropIfExists('sys_mail');
    }
}
