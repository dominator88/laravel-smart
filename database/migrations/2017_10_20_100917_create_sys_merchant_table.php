<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_merchant', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('phone' , 20);
            $table->string('contact',30)->nullable();
            $table->string('email',200)->nullable();
            $table->string('id_card')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('area')->nullable();
            $table->string('address',200)->nullable();
            $table->decimal('settled_amount' , 10,2)->nullable();
            $table->decimal('balance' , 10 ,2)->nullable();
            $table->decimal('withdraw_amount' , 10,2)->nullable();
            $table->integer('apply_user_id'  )->nullable();
            $table->tinyInteger('for_test')->default(0);

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
        Schema::dropIfExists('sys_merchant');
    }
}
