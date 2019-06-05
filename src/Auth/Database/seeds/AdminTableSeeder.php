<?php
namespace Smart\Auth\Database;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		//新增管理员
		$user_id = DB::table('sys_user')->insertGetId(
			[
				'module' => 'backend',
				'username' => config('backend.defaultAdmin'),
				'password' => bcrypt(config('backend.defaultPwd')),
				'status' => 1,
				'name' => 'admin',
				'email' => config('backend.defaultEmail'),
				'icon' => '',
				'phone' => '',
				'api_token' => '',
				'signed_at' => Carbon::now(),
				'signed_ip' => '',
			]
		);

		$role_id = DB::table('sys_role')->insertGetId(
			[
				'sort' => 10,
				'module' => 'backend',
				'name' => '系统管理员',
				'status' => 1,
				'rank' => 5,
				'mer_id' => 0,
				'desc' => '',

			]
		);

		DB::table('sys_user_role')->insert([
			[
				'user_id' => $user_id,
				'role_id' => $role_id,
			],
		]);

		//默认功能菜单模板
		$default_func = [
			'pid' => 0,
			'module' => 'backend',
			'is_menu' => 1,
			'is_func' => 0,
			'color' => 'default',
			'level' => 1,
			'status' => 1,
			'uri' => '',
			'icon' => '',
			'name' => '',
			'sort' => 1,
			'desc' => '',
		];

		//新增权限
		DB::table('sys_func')->insert([
			//一级菜单
			extend($default_func, [
				'name' => '首页',
				'icon' => 'icon-home',
				'uri' => 'backend/index/index',
			]),

			extend($default_func, [
				'name' => '系统',
				'icon' => 'icon-settings',
			]),
			extend($default_func, [
				'name' => '平台用户',
				'icon' => 'icon-users',
			]),
			extend($default_func, [
				'name' => '机构',
				'icon' => 'icon-globe',
			]),
			extend($default_func, [
				'name' => '工具',
				'icon' => 'icon-wrench',
			]),

			//二级菜单 系统
			extend($default_func, [
				'name' => '系统功能',
				'icon' => '',
				'uri' => 'backend/sysfunc/index',
				'pid' => 2,
				'level' => 2,
			]),
			extend($default_func, [
				'name' => '系统角色',
				'icon' => '',
				'uri' => 'backend/sysrole/index',
				'pid' => 2,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '系统用户',
				'icon' => '',
				'uri' => 'backend/sysuser/index',
				'pid' => 2,
				'level' => 2,
			]),
			/*extend($default_func , [
				                    'name' => '系统用户组',
				                    'icon'  => '',
				                    'uri'   => 'backend/sysgroup/index',
				                    'pid'   => 2,
				                    'level' => 2
			*/

			//二级菜单 工具
			extend($default_func, [
				'name' => 'APP版本管理',
				'icon' => '',
				'uri' => 'backend/sysappversion/index',
				'pid' => 5,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '消息推送',
				'icon' => '',
				'uri' => 'backend/syspush/index',
				'pid' => 5,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '短信',
				'icon' => '',
				'uri' => 'backend/syssms/index',
				'pid' => 5,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '邮件',
				'icon' => '',
				'uri' => 'backend/sysmail/index',
				'pid' => 5,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '代码生成',
				'icon' => '',
				'uri' => 'backend/generate/index',
				'pid' => 5,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '接口模拟器',
				'icon' => '',
				'uri' => 'backend/simulator/index',
				'pid' => 5,
				'level' => 2,
			]),

			//机构
			extend($default_func, [
				'name' => '机构管理',
				'icon' => '',
				'uri' => 'backend/sysmerchant/index',
				'pid' => 4,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '机构功能',
				'icon' => '',
				'uri' => 'backend/merfunc/index',
				'pid' => 4,
				'level' => 2,
			]),

			extend($default_func, [
				'name' => '机构角色',
				'icon' => '',
				'uri' => 'backend/merrole/index',
				'pid' => 4,
				'level' => 2,
			]),

			//平台用户
			extend($default_func, [
				'name' => '平台用户管理',
				'icon' => '',
				'uri' => 'backend/meruser/index',
				'pid' => 3,
				'level' => 2,
			]),



		]
		);
	}
}
