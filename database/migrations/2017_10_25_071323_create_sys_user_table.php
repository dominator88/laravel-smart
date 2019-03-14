<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSysUserTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('sys_user', function (Blueprint $table) {
			$table->increments('id');
			$table->string('module')->default('backend');
			$table->string('username');
			$table->string('password');
			$table->string('icon')->nullable();
			$table->string('email')->nullable();
			$table->string('phone')->nullable();
			$table->tinyInteger('status')->default(1);
			$table->string('api_token', 64)->nullable();
			$table->timestamps();
			$table->dateTime('signed_at')->nullable();
			$table->ipAddress('signed_ip')->nullable();
			$table->rememberToken()->nullable();
			$table->string('name')->nullable();
			$table->unique('email')->nullable();
			$table->unique('username')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('sys_user');
	}
}
