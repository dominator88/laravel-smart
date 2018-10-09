<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerGoodsIconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_goods_icon', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('sort')->default(99);
            $table->integer('goods_id')->unsigned();
            $table->string('uri' , 200)->nullable();
            $table->tinyInteger('is_cover')->default(0);
            $table->foreign('goods_id')->references('id')->on('mer_goods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mer_goods_icon');
    }
}
