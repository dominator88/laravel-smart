<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerArticlesCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_articles_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0);
            $table->integer('mer_id');
            $table->tinyInteger('sort')->default(99);
            $table->string('text' , 200);
            $table->string('icon' , 200)->nullable();
            $table->string('desc' , 200)->nullable();
            $table->tinyInteger('level' )->default(1);
            $table->tinyInteger('status' )->default(1);
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
        Schema::dropIfExists('mer_articles_catalog');
    }
}
