<?php
namespace Smart\Controllers\Backend;
/**
 * SysModules Controller
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2018-06-13
 */

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Smart\Service\SysModulesService;

class SysModules extends Backend {

	/**
	 * SysModules constructor.
	 */
	public function __construct(Request $request) {
		parent::__construct($request);
		$this->_initClassName($this->controller);
		$this->service = ServiceManager::make(SysModulesService::class);
	}

	//页面入口
	public function index(Request $request) {
		$this->_init('模块列表');

		//uri
		$this->_addParam('uri', [
			'modulefunc' => 'backend/modulefunc/index',
		]);

		//查询参数
		$this->_addParam('query', [
			'keyword' => $request->input('keyword', ''),
			'status' => $request->input('status', ''),
			'page' => $request->input('page', 1),
			'pageSize' => $request->input('pageSize', 10),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
		]);

		//其他参数
		$this->_addParam([
			'defaultRow' => $this->service->getDefaultRow(),
			'status' => $this->service->status,
		]);

		//需要引入的 css 和 js

		$this->_addJsLib('static/plugins/dmg-ui/TableGrid.js');

		return $this->_displayWithLayout('backend::sysmodules.index');
	}

	/**
	 * 读取
	 * @return response->Json
	 */
	public function read(Request $request) {
		$config = [
			'status' => $request->input('status', ''),
			'keyword' => $request->input('keyword', ''),
			'page' => $request->input('page', 1),
			'pageSize' => $request->input('pageSize', 10),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
		];

		$data['rows'] = $this->service->getByCond($config);
		$config['count'] = TRUE;
		$data['total'] = $this->service->getByCond($config);

		return json(ajax_arr('查询成功', 0, $data));
	}

}