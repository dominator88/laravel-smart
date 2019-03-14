<?php namespace Smart\Controllers\Backend;
/**
 * MerAlbum Controller
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-11-28
 */

use Facades\Smart\Service\ServiceManager;
use Illuminate\Http\Request;
use Smart\Service\MerAlbumService;
use Smart\Service\MerAlbumCatalogService;
use Smart\Controllers\Backend\Backend;


class MerAlbum extends Backend{

	/**
	 * MerAlbum constructor.
	 */


	//页面入口
	public function index(Request $request ) {
        $id = $request->route('id');
        $albums = $this->service->getModel()->find($id);
		$this->_init( 'MerAlbum' );

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
        $this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );

		return $this->_displayWithLayout('backend::MerAlbum.index');
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


    public function upload(Request $request){
        $param          = $request->all( );
        $param['isKE']  = $request->input( 'isKE' , 0 );
        $param['merId'] = $this->merId;

        $Upload = ServiceManager::make( UploadService::class );

        return json( $Upload->doUpload( $param ) );
    }

    public function read_album(Request $request){
        $MerAlbum = MerAlbumService::instance();

        $config = [
            'field'    => [ 'id' , 'uri' , 'mimes' , 'desc' , 'img_size' ] ,
            'merId'    => $this->merId ,
            'catalog'  => $request->input( 'catalog' , '' ) ,
            'sort'     => 'id' ,
            'order'    => 'DESC' ,
            'status'   => 1 ,
            'page'     => $request->input( 'page' , 1 ) ,
            'pageSize' => $request->input( 'pageSize' , 12 ) ,
        ];

        $result['rows']  = $MerAlbum->getByCond( $config );
        $config['count'] = TRUE;
        $result['total'] = $MerAlbum->getByCond( $config );

        return json( $result );
    }

    /**
     * 取相册分类
     *
     * @return Json
     */
    public function read_album_catalog() {
        $MerAlbumCatalog = MerAlbumCatalogService::instance();
        $result          = $MerAlbumCatalog->getByCond( [
            'field'  => [ 'id' , 'tag' ] ,
            'merId'  => $this->merId ,
            'sort'   => 'sort' ,
            'order'  => 'ASC' ,
            'status' => 1 ,
            'getAll' => TRUE
        ] );

        return  json( $result );
    }



	

}