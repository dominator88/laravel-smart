<?php namespace Smart\Controllers\Backend;
/**
 * SysPermissionNode Controller
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2019-05-28
 */

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Smart\Service\SysPermissionNodeService;
use Smart\Service\SysFuncService;
use Smart\Models\Role;
use Smart\Models\Permission;

class SysPermissionNode extends Backend {



	//页面入口
	public function index(Request $request) {
	
		$this->_init( 'SysPermissionNode' );

		//uri
		$this->_addParam( 'uri', [
			
			
			
		] );

		$modules = explode(',',config('backend.module_ext'));
        $modules = array_combine($modules, $modules);

		//查询参数
		$this->_addParam( 'query', [
			'keyword'  => $request->input( 'keyword', '' ),
			'status'   => $request->input( 'status', '' ),
			'page'     => $request->input( 'page', 1 ),
			'pageSize' => $request->input( 'pageSize', 10 ),
			'sort'     => $request->input( 'sort', 'id' ),
			'order'    => $request->input( 'order', 'DESC' ),
		] );



		//其他参数
		$this->_addParam( [
			'defaultRow' => $this->service->getDefaultRow() ,
			'status' => $this->service->status ,
			'modules'    => $modules,   
		] );

		//需要引入的 css 和 js
		
		
		
		

		
		$this->_addJsLib( 'static/plugins/dmg-ui/TreeGrid.js' );

		return $this->_displayWithLayout('backend::syspermissionnode.index');
	}

	
	/**
 * 读取
 * @return response->Json
 */
	public function read(Request $request) {
		$config = [
			'func_id'	=> $request->input( 'func_id', ''),
			'module'	=> $request->input( 'module', '' ),
			'status'    => $request->input( 'status', '' ),
			'keyword'   => $request->input( 'keyword', '' ),
			'sort'      => $request->input( 'sort', 'id' ),
			'order'     => $request->input( 'order', 'DESC' ),
		];

		$data['rows']    = $this->service->getByCond( $config );

		return json( ajax_arr( '查询成功', 0, $data ) );
	}

	public function getPrivilege(Request $request){
		$menu_id = $request->menu_id;
		$sysFuncService = ServiceManager::make(SysFuncService::class);
		$symbol = $sysFuncService->getSymbol($menu_id);

		$config = [
			'module'  => $request->input( 'module', 'backend' ),
			'status'    => 1,
			'type' => 'func',
			'symbol'    => $symbol,
			'sort'      => $request->input( 'sort', 'id' ),
			'order'     => $request->input( 'order', 'DESC' ),
		];

		$data['rows']    = $this->service->getPrivilege( $config );


		return json( ajax_arr('查询成功', 0, $data));
	}

}