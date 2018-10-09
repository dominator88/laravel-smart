<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_order_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('goods_id');
            $table->string('goods_name' , 200)->nullable();
            $table->string('icon' , 200)->nullable();
            $table->enum('currency' , ['cny' , 'points' , ])->nullable();
            $table->decimal('amount' , 11,2)->default(0.00);
            $table->integer('qty');
            $table->integer('event_id')->nullable();
            $table->integer('event_amount')->default(0);
            $table->integer('coupon_id')->nullable();
            $table->integer('coupon_amount')->default(0);
            $table->integer('get_points')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mer_order_items');
    }
}
