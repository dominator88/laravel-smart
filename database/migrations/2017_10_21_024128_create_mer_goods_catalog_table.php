<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerGoodsCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_goods_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id');
            $table->integer('pid');
            $table->string('text');
            $table->string('icon');
            $table->string('desc');
            $table->tinyInteger('sort');
            $table->tinyInteger('level');
            $table->tinyInteger('status');
            $table->enum('type' ,  ['goods' , 'virtual' , 'service']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mer_goods_catalog');
    }
}
