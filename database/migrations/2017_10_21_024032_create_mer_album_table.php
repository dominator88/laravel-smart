<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerAlbumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_album', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id');
            $table->integer('sort');
            $table->string('uri',200);
            $table->integer('size');
            $table->string('mimes', 50);
            $table->string('img_size', 200);
            $table->string('desc');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('mer_album');
    }
}
