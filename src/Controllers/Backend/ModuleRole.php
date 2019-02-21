<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 10:18
 */
namespace Smart\Controllers\Backend;

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Smart\Service\SysFuncPrivilegeService;
use Smart\Service\SysFuncService;
use Smart\Service\SysModuleService;
use Smart\Service\SysRolePermissionService;
use Smart\Service\SysRoleService;

class ModuleRole extends Backend {
	/**
	 * SysRole constructor.
	 */


	//页面入口
	public function index(Request $request) {
		$this->_init('模块角色管理');

		//uri
		$this->_addParam('uri', [
			'getPermission' => full_uri('backend/sysrole/get_permission'),
			'getPrivilegeData' => full_uri('backend/sysrole/get_privilegeData'),
			'updatePermission' => full_uri('backend/sysrole/update_permission'),
		]);

		//查询参数
		$this->_addParam('query', [
			'id' => $request->input('id', ''),
			'keyword' => $request->input('keyword', ''),
			'status' => $request->input('status', ''),
			'page' => $request->input('page', 1),
			'pageSize' => $request->input('pageSize', 10),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
		]);
		$sysModule = ServiceManager::make(SysModuleService::class);
		$module = $sysModule->getById($request->input('id', ''));
		//其他参数
		$this->_addParam([
			'defaultRow' => $this->service->getDefaultRow(),
			'status' => $this->service->status,
			'rank' => $this->service->rank,
			'module_name' => $module->symbol,
			'nav' => Route::currentRouteName(),
			'func_uri' => route('backend.modulefunc.index', ['id' => $request->input('id', '')]),
			'role_uri' => route('backend.modulerole.index', ['id' => $request->input('id', '')]),
		]);

		//需要引入的 css 和 js
		$this->_addJsLib('static/plugins/dmg-ui/TableGrid.js');

		return $this->_displayWithLayout('backend::modulerole.index');
	}

	//读取
	function read(Request $request) {
		$module_name = ServiceManager::make(SysModuleService::class)->getById($request->input('id'))->symbol;
		$param = [
			'module' => $module_name,
			'status' => $request->input('status', ''),
			'keyword' => $request->input('keyword', ''),
			'page' => $request->input('page', 1),
			'pageSize' => $request->input('pageSize', 10),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
		];

		$data['rows'] = $this->service->getByCond($param);
		$param['count'] = TRUE;
		$data['total'] = $this->service->getByCond($param);

		return response()->json(ajax_arr('查询成功', 0, $data));
	}

	function get_permission(Request $request) {
		$roleId = $request->input('roleId');
		$SysFuncPrivilege = SysFuncPrivilegeService::instance();
		$data['privilegeName'] = $SysFuncPrivilege->name;
		//取角色操作权限

		//取所有功能与操作
		$SysFunc = SysFuncService::instance();
		$data['funcData'] = $SysFunc->getByCond([
			'module' => 'backend',
			'status' => 1,
			'withPrivilege' => TRUE,
		]);

//var_dump(view( 'sysrole/index' ));
		//   var_dump($data);
		//return view( 'sysrole/permission' )->with($data);

		return view('backend::sysrole/permission')->with($data);

		//  return $ret;
	}

	function get_privilegeData(Request $request) {
		$roleId = $request->input('roleId');
		$SysRolePermission = SysRolePermissionService::instance();
		return response()->json($SysRolePermission->getByRole($roleId));
	}

	//更新授权
	function update_permission(Request $request) {
		$roleId = $request->input('roleId');
		$privilegeArr = $request->input('privilegeArr');

		$SysRolePermission = SysRolePermissionService::instance();
		$ret = $SysRolePermission->updateRolePermission($roleId, $privilegeArr);

		return response()->json($ret);
	}

}