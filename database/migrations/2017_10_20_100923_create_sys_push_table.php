<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysPushTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_push', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id');
            $table->enum('catalog' , ['alert' , 'order' , 'event']);
            $table->string('title');
            $table->string('alert');
            $table->enum('platform' , ['all' , 'ios' , 'android']);
            $table->string('alias');
            $table->string('tags');
            $table->string('registration_id', 40);
            $table->string('extras');
            $table->tinyInteger('status');
            $table->dateTime('sent_at');
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
        Schema::dropIfExists('sys_push');
    }
}
