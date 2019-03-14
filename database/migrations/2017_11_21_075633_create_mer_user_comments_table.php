<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerUserCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mer_user_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->enum('type' , ['article' , 'goods' , 'event'])->default('article');
            $table->integer('type_id')->comment('对象ID')->default(0);
            $table->text('content')->comment('内容');
            $table->text('reply')->comment('回复内容');
            $table->dateTime('replied_at');
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
        Schema::dropIfExists('mer_user_comments');
    }
}
