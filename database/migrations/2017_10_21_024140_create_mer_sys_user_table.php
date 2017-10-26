<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerSysUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_sys_user', function (Blueprint $table) {
           /* $table->increments('id');*/
            $table->integer('id');
            $table->integer('mer_id');
            $table->integer('sys_user_id');
            $table->index('mer_id' ,'mer_id' );
            $table->index('sys_user_id' , 'sys_user_id');
            $table->primary(['id','mer_id', 'sys_user_id'] , 'id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mer_sys_user');
    }
}
