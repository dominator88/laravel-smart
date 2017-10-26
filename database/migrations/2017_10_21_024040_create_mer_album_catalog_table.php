<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerAlbumCatalogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_album_catalog', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mer_id');
            $table->integer('sort');
            $table->string('tag' , 200);
            $table->string('icon' , 200);
            $table->integer('totals' );
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
        Schema::dropIfExists('mer_album_catalog');
    }
}
