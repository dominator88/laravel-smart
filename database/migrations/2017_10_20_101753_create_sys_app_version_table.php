<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysAppVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_app_version', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('device' ,['ios' , 'android']);
            $table->string('version' ,30);
            $table->string('uri' , 200);
            $table->string('description' , 2000);
            $table->tinyInteger('is_force'  );
            $table->enum('environment' , ['production' , 'test' , 'debug']);
            $table->tinyInteger('status' );
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
        Schema::dropIfExists('sys_app_version');
    }
}
