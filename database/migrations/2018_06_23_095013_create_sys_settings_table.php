<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysSettingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('sys_settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('key')->default('')->comment('名称');
			$table->string('value')->default('')->comment('配置值');
			$table->string('group')->default('default')->comment('配置分组');
			$table->string('type')->default('text')->comment('配置类型 text radio');
			$table->integer('desc')->default(99)->comment('排序');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sys_settings');
	}
}
