<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerAdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_ad', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id')->nullable();
            $table->string('name');
            $table->tinyInteger('sort')->default(99);
            $table->integer('catalog_id')->unsigned();
            $table->foreign('catalog_id')->references('id')->on('sys_merchant');
            $table->string('icon')->default('');
            $table->string('uri')->nullable();
            $table->integer('pv')->default(0);
            $table->tinyInteger('status')->default(1);

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
        Schema::dropIfExists('mer_ad');
    }
}
