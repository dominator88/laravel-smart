<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('mer_id');
            $table->enum('type' ,  ['goods' , 'virtual' , 'service'])->default('goods');
            $table->string('order_no' , 16);
            $table->integer('address_id');
            $table->string('address_name' , 200);
            $table->string('address_phone' , 20 );
            $table->string('address' , 500);
            $table->string('address_post' , 6)->nullable();
            $table->integer('event_id' )->nullable();
            $table->integer('event_amount' )->default(0);
            $table->integer( 'coupon_id')->nullable();
            $table->decimal('coupon_amount' , 8, 2)->default(0.00);
            $table->decimal('bucks' , 8, 2)->default(0.00);
            $table->enum('currency' , ['cny' , 'points'])->default('cny');
            $table->decimal('amount' , 8,2)->default(0.00);
            $table->enum('pay_channel',['alipay' , 'wx' , 'points'])->default('alipay');
            $table->decimal('pay_amount' , 11,2)->default(0.00);
            $table->tinyInteger('status' )->default(1);
            $table->integer('get_points' )->default(0);
            $table->integer('pay_id' )->nullable();
            $table->string('user_mark' )->nullable();
            $table->string('sys_mark' )->nullable();
            $table->dateTime('pay_time');
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
        Schema::dropIfExists('mer_order');
    }
}
