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
            $table->integer('mer_id')->nullable();
            $table->integer('catalog_id');
            $table->integer('sort')->default(999);
            $table->string('title' , 200);
            $table->string('icon' , 200)->nullable();
            $table->string('tags' , 200)->nullable();
            $table->string('desc' , 200)->nullable();
            $table->text('content')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->integer('comments')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('pv')->default(0);
            $table->integer('favorites')->default(0);
            $table->integer('userId')->default(1);


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
