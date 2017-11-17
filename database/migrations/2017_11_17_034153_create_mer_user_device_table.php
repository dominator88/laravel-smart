<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerUserDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_user_device', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('token', 64)->nullable();
            $table->enum('device' ,  ['iphone' , 'ipad' , 'android' , 'pc' , 'mac' , 'unknow'])->nullable();
            $table->string('device_os_version' , 20)->nullable();
            $table->string('app_version' , 20)->nullable();
            $table->string('api_version' , 20)->nullable();
            $table->string('registration_id' , 50)->nullable();
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
        Schema::dropIfExists('mer_user_device');
    }
}
