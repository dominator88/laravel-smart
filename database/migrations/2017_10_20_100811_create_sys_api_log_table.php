<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysApiLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_api_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device')->nullable();
            $table->string('device_os_version')->nullable();
            $table->string('app_version')->nullable();
            $table->string('api_version')->nullable();
            $table->string('uri')->nullable();
            $table->string('ip')->nullable();
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
        Schema::dropIfExists('sys_api_log');
    }
}
