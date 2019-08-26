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
use Smart\Service\SysFuncPrivilegeService;
use Smart\Service\SysFuncService;

class SysFunc extends Backend {


	public function index(Request $request) {
		$this->_init('系统功能'); 

		//uri
		$this->_addParam('uri', [
			'updatePrivilege' => full_uri('Backend/SysFunc/update_privilege', ['funcId' => '']),
		]);
		//查询参数
		$this->_addParam('query', [
			'module' => 'backend',
			'keyword' => $request->input('keyword', ''),
			'status' => $request->input('status', ''),
			'page' => $request->input('page', 1),
			'pageSize' => $request->input('pageSize', 10),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
		]);

		//其他参数
		$SysFuncPrivilege = SysFuncPrivilegeService::instance();
		$this->_addParam([
			'defaultRow' => $this->service->getDefaultRow(),
			'status' => $this->service->status,
			'isMenu' => $this->service->isMenu,
			'isFunc' => $this->service->isFunc,
			//'privilege' => $SysFuncPrivilege->name,
			//'alias' => $SysFuncPrivilege->alias,
		]);

		//需要引入的 css 和 js
		$this->_addJsLib('static/plugins/dmg-ui/TreeGrid.js');

		//  var_dump($request->server());exit;
		return $this->_displayWithLayout('backend::sysfunc/index');

	}

	/**
	 * 读取
	 */
	function read(Request $request) {
		$config = [
			'module' => $request->input('module',''),
			'status' => $request->input('status', ''),
			'keyword' => $request->input('keyword', ''),
			'page'     => $request->input( 'page', 1 ),
	    	'pageSize' => $request->input( 'pageSize', 10 ),
			'sort' => $request->input('sort', 'id'),
			'order' => $request->input('order', 'DESC'),
			'module' => 'backend',
			'withPrivilege' => TRUE,
		];

		$data['rows']    = $this->service->getByCond( $config );
		$config['count'] = TRUE;
		$data['total']   = $this->service->getByCond( $config );

		return response()->json(ajax_arr('查询成功', 0, $data));
	}

	/**
	 * 更新权限
	 *
	 */
	function update_privilege(Request $request) {
		$funcId = $request->funcId;
		$data = $request->all();

		$SysFuncPrivilege = SysFuncPrivilegeService::instance();
		$ret = $SysFuncPrivilege->updateByFunc($funcId, $data);

		return response()->json($ret);
	}

}