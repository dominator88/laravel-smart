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
            $table->integer('mer_id')->default(0);
            $table->integer('pid')->default(0);
            $table->string('text')->nullable();
            $table->string('icon')->nullable();
            $table->string('desc')->nullable();
            $table->tinyInteger('sort')->default(1);
            $table->tinyInteger('level')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->enum('type' ,  ['goods' , 'virtual' , 'service'])->default('goods');

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
