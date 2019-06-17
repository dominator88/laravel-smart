<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysPermissionNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_permission_node', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('节点名称');
            $table->string('module')->comment('模块');
            $table->integer('pid')->default(0)->comment('父级id');
            $table->integer('level')->default(1)->comment('层级');
            $table->string('type')->default('func')->comment('权限类型 func category');
            $table->string('symbol')->nullable()->comment('标识');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->integer('sort')->default(99)->comment('排序');
            $table->integer('permission_id')->default(0)->comment('权限id');
            $table->integer('func_id')->default(0)->comment('方法id');
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
        Schema::dropIfExists('sys_permission_node');
    }
}
