<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id');
            $table->integer('catalog_id');
            $table->integer('sort');
            $table->string('title');
            $table->string('icon');
            $table->string('tags');
            $table->string('desc');
            $table->text('content');
            $table->tinyInteger('status');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->integer('comments');
            $table->integer('likes');
            $table->integer('pv');
            $table->integer('favorites');
            $table->integer('userId');
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
        Schema::dropIfExists('mer_articles');
    }
}
