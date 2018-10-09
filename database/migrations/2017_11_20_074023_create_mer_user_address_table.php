<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerUserAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_user_address', function (Blueprint $table) {

            $table->increments('uid');
            $table->integer('user_id');
            $table->string('name' , 200)->nullable();
            $table->string('phone' , 20)->nullable();
            $table->integer('area_id');
            $table->string('address' , 500)->nullable();
            $table->string('postcode' , 10)->nullable();
            $table->tinyInteger('status' )->default(1);
            $table->tinyInteger('is_default' )->default(0);
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
        Schema::dropIfExists('mer_user_address');
    }
}
