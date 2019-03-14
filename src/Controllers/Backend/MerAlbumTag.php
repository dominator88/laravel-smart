<?php namespace Smart\Controllers\Backend;
/**
 * MerAlbumTag Controller
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-11-28
 */

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Smart\Service\MerAlbumTagService;
use Smart\Controllers\Backend\Backend;

class MerAlbumTag extends Backend {

	/**
	 * MerAlbumTag constructor.
	 */


	//页面入口
	public function index(Request $request) {
		$this->_init( 'MerAlbumTag' );

		//uri
		$this->_addParam( 'uri', [
			
			
			
		] );

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
		] );

		//需要引入的 css 和 js
		
		
		
		

		$this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );
    

		return $this->_displayWithLayout('Backend::MerAlbumTag.index');
	}

	/**
 * 读取
 * @return response->Json
 */
public function read(Request $request) {
  $config = [
    'status'   => $request->input( 'status', '' ),
    'keyword'  => $request->input( 'keyword', '' ),
    'page'     => $request->input( 'page', 1 ),
    'pageSize' => $request->input( 'pageSize', 10 ),
    'sort'     => $request->input( 'sort', 'id' ),
    'order'    => $request->input( 'order', 'DESC' ),
  ];

  $data['rows']    = $this->service->getByCond( $config );
  $config['count'] = TRUE;
  $data['total']   = $this->service->getByCond( $config );

  return json(ajax_arr( '查询成功', 0, $data ) );
}
	

}