<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sys_goods_id')->nullable();
            $table->integer('sys_goods_pid')->nullable();
            $table->integer('mer_id')->nullable();
            $table->integer('pid')->default(0);
            $table->integer('sort')->default(999);
            $table->string('sku' , 50)->nullable()->unique();
            $table->string('name' , 200)->unique();
            $table->integer('catalog_id')->default(0);
            $table->text('highlight' ,400)->nullable();
            $table->string('icon' ,200)->nullable();
            $table->string('desc' , 200)->nullable();
            $table->string('tags' , 300)->nullable();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->enum('currency' , ['cny' , 'points'])->default('cny');
            $table->decimal('price_market',8,2)->default('0.00');
            $table->decimal('price',8,2)->default('0.00');
            $table->integer('points')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->string('meta_title' , 100)->nullable();
            $table->string('meta_keywords' , 1000)->nullable();
            $table->string('meta_description' , 1000)->nullable();
            $table->tinyInteger('recommend' )->default(0);
            $table->tinyInteger('hot')->default(0);
            $table->tinyInteger('cheap')->default(0);
            $table->integer('sales')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('pv')->default(0);


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
        Schema::dropIfExists('mer_goods');
    }
}
