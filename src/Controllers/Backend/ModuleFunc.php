<?php namespace Smart\Controllers\Backend;
/**
 * SysFunc Controller
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2018-06-15
 */

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Smart\Service\SysFuncPrivilegeService;
use Smart\Service\SysFuncService;
use Smart\Service\SysModulesService;

class ModuleFunc extends Backend {

	/**
	 * SysFunc constructor.
	 */
	public function __construct(Request $request) {
		parent::__construct($request);
		$this->_initClassName($this->controller);
		$this->service = ServiceManager::make(SysFuncService::class);
	}

	//页面入口
	public function index(Request $request) {

		$this->_init('系统功能');

		//uri
		$this->_addParam('uri', [
			'updatePrivilege' => full_uri('Backend/SysFunc/update_privilege', ['funcId' => '']),
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

		$sysModules = ServiceManager::make(SysModulesService::class);
		$module = $sysModules->getById($request->input('id', ''));
		//其他参数
		$SysFuncPrivilege = SysFuncPrivilegeService::instance();
		$this->_addParam([
			'defaultRow' => array_merge($this->service->getDefaultRow(), ['module' => $module->symbol]),
			'status' => $this->service->status,
			'isMenu' => $this->service->isMenu,
			'isFunc' => $this->service->isFunc,
			'privilege' => $SysFuncPrivilege->name,
			'alias' => $SysFuncPrivilege->alias,
			'module_name' => $module->symbol,
			'nav' => Route::currentRouteName(),
			'func_uri' => route('backend.modulefunc.index', ['id' => $request->input('id', '')]),
			'role_uri' => route('backend.modulerole.index', ['id' => $request->input('id', '')]),
		]);

		//需要引入的 css 和 js
		$this->_addJsLib('static/plugins/dmg-ui/TreeGrid.js');

		return $this->_displayWithLayout('backend::ModuleFunc.index');
	}

	/**
	 * 读取
	 * @return response->Json
	 */
	public function read(Request $request) {
		$module_name = ServiceManager::make(SysModulesService::class)->getById($request->input('id'))->symbol;
		$config = [
			'module' => $module_name,
			'status' => $request->input('status', ''),
			'keyword' => $request->input('keyword', ''),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
			'withPrivilege' => TRUE,
		];

		$data['rows'] = $this->service->getByCond($config);

		return json(ajax_arr('查询成功', 0, $data));
	}

}