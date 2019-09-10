<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysFuncExtendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_func_extends', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('func_id')->comment('funcid');
            $table->string('extend_name')->nullable()->comment('name');
            $table->string('extend_path')->nullable()->comment('path');
            $table->string('extend_component')->nullable()->comment('component');
            $table->boolean('extend_notCache')->default(false)->comment('notCache');
            $table->boolean('extend_showAlways')->default(false)->comment('showAlways');

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
        Schema::dropIfExists('sys_func_extends');
    }
}
