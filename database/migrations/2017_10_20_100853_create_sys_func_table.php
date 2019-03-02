<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysFuncTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_func', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0);
            $table->tinyInteger('sort')->default(1);
            $table->string('module',40);
            $table->tinyInteger('is_menu')->default(0);
            $table->tinyInteger('is_func')->default(0);
            $table->string('color')->nullable();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('uri')->nullable();
            $table->string('desc')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('level')->default(1);

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
        Schema::dropIfExists('sys_func');
    }
}
