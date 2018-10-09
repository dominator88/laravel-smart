<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerAdCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_ad_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id')->nullable();
            $table->string('text' , 200)->nullable();
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
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
        Schema::dropIfExists('mer_ad_catalog');
    }
}
