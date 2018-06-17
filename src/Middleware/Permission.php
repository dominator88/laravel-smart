<?php

namespace Smart\Middleware;

use Closure;
use Facades\Smart\Service\ServiceManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Smart\Service\SysFuncService;
use Smart\Service\SysRoleService;
use Smart\Service\SysUserService;

class Permission {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {

		// 获取当前路由名称  module.func.permission
		$name = Route::currentRouteName();
		$route = explode('.', $name);
		list($module, $func, $privilege) = $route;
		$sysUserService = ServiceManager::make(SysUserService::class);
		$roles = $sysUserService->getById(Auth::id())->sysRole()->get();

		$role = $roles->firstWhere('id', config('backend.superAdminId'));
		if ($role) {
			return $next($request);
		}

		if (empty($privilege)) {
			return response()->json(['msg' => '校验权限必填', 'code' => 422]);
		}
		//当前操作对应的id

		$funcUri = $module . '/' . $func . '/index';

		$sysFuncService = ServiceManager::make(SysFuncService::class);
		$sysFunc = $sysFuncService->getByUri($funcUri);

		$data = $sysFunc->sysFuncPrivileges()->where('name', $privilege == 'index' ? 'read' : $privilege)->first();

		//当前
		if (empty($data)) {
			return response()->json(['msg' => '当前用户未设置任何权限', 'code' => 422]);
		}

		$privilege_id = $data->id;
		$sysRoleService = ServiceManager::make(SysRoleService::class);

		//筛选出符合条件的角色
		foreach ($roles as $role) {

			//从角色中筛选出含该功能的角色
			$sysRole = $sysRoleService->getById($role->id);
			$data = $sysRole::with('sysFuncPrivileges')->get();

			foreach ($data as $d) {
				//只要有一个角色符合条件,就说明验证通过
				$result = $d->sysFuncPrivileges->firstWhere('id', $privilege_id);

				if ($result) {
					return $next($request);
				}
			}
			//从符合条件的角色中筛选出符号当前操作的角色

		}

		return response()->json(['msg' => '无此权限', 'code' => 422]);
	}
}
