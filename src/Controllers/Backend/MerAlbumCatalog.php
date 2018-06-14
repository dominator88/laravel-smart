<?php namespace Smart\Controllers\Backend;
/**
 * MerAlbumCatalog Controller
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-11-28
 */

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Smart\Service\MerAlbumCatalogService;
use Smart\Controllers\Backend\Backend;

class MerAlbumCatalog extends Backend {

	/**
	 * MerAlbumCatalog constructor.
	 */
	public function __construct(Request $request) {
		parent::__construct($request);
		$this->_initClassName( $this->controller );
		$this->service = ServiceManager::make(  MerAlbumCatalogService::class );
	}

	//页面入口
	public function index(Request $request) {
		$this->_init( 'MerAlbumCatalog' );

		//uri
		$this->_addParam( 'uri', [
			'upload'       => full_uri( 'backend/meralbumcatalog/upload' ),
'albumCatalog' => full_uri( 'backend/meralbumcatalog/read_album_catalog' ),
'album'        => full_uri( 'backend/meralbum/index' ),
			
			
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

    //上传参数
$this->_addParam('uploadParam' , [
  'width'       => 300 ,
  'height'      => 300 ,
  'saveAsAlbum' => TRUE,
  'albumTag'    => '默认相册',
]);

//相册参数
$this->_addParam( 'albumParam', [
  'defaultTag' => '默认相册',
  'pageSize'   => 12,
] );

		//其他参数
		$this->_addParam( [
			'defaultRow' => $this->service->getDefaultRow() ,
			'status' => $this->service->status ,
		] );

		//需要引入的 css 和 js
		
		
		$this->_addCssLib('node_modules/jcrop-0.9.12/css/jquery.Jcrop.min.css');
$this->_addJsLib('node_modules/jcrop-0.9.12/js/jquery.Jcrop.min.js');
$this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );
		

		$this->_addJsLib( 'static/plugins/dmg-ui/tiles.js' );
    

		return $this->_displayWithLayout('backend::MerAlbumCatalog.index');
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