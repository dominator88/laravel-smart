<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysUserDeviceTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('sys_user_device', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');

			$table->enum('device', ['iphone', 'ipad', 'android', 'pc', 'mac', 'unknow'])->nullable();
			$table->string('device_os_version', 20)->nullable();
			$table->string('app_version', 20)->nullable();
			$table->string('api_version', 20)->nullable();
			$table->string('registration_id', 50)->nullable();
			$table->tinyInteger('for_test')->default(0)->comment('是否测试账户');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sys_user_device');
	}
}
