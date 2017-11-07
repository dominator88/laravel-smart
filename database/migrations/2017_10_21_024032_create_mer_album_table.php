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
            $table->integer('mer_id')->default(0);
            $table->integer('sort')->default(1);
            $table->string('uri',200)->nullable();
            $table->integer('size')->default(0);
            $table->string('mimes', 50)->nullable();
            $table->string('img_size', 200)->default(0);
            $table->string('desc')->nullable();
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
        Schema::dropIfExists('mer_album');
    }
}
