<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysModulesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('sys_modules', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->comment('模块名称');
			$table->string('symbol')->comment('标识');
			$table->tinyInteger('displayorder')->default(0)->comment('排序序号');
			$table->string('version')->default('1.0')->comment('版本号');
<<<<<<< HEAD
			$table->string('author')->nullable()->comment('作者');
			$table->tinyInteger('status')->default(1)->comment('状态');
			$table->string('thumb')->nullable()->comment('缩略图');
			$table->string('desc')->nullable()->comment('描述');
=======
			$table->string('author')->comment('作者');
			$table->tinyInteger('status')->comment('状态');
			$table->string('thumb')->comment('缩略图');
			$table->string('desc')->comment('描述');
>>>>>>> merge
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sys_modules');
	}
}
