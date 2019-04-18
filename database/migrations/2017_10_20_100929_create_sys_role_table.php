<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_role', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('sort')->default(0);
            $table->string('module' )->default('backend')->comment('模块');
            $table->integer('mer_id')->default(0);
            $table->string('name');
            $table->tinyInteger('status');
            $table->string('desc')->nullable();
            $table->tinyInteger('rank')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sys_role');
    }
}
