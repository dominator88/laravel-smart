<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('users_total' );
            $table->integer('users_today' );
            $table->integer('api' );
            $table->integer('articles_total' );
            $table->integer('articles_today' );
            $table->integer('videos_total' );
            $table->integer('videos_today' );
            $table->date('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_statistics');
    }
}
