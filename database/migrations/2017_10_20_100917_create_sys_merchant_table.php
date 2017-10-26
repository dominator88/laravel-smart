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
            $table->string('icon');
            $table->string('phone' , 20);
            $table->string('contact',30);
            $table->string('email',200);
            $table->string('id_card');
            $table->tinyInteger('status');
            $table->integer('area');
            $table->string('address',200);
            $table->decimal('settled_amount' , 10,2);
            $table->decimal('balance' , 10 ,2);
            $table->decimal('withdraw_amount' , 10,2);
            $table->integer('apply_user_id'  );
            $table->tinyInteger('for_test');

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
