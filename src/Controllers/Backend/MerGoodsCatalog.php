<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/21
 * Time: 09:33
 */

namespace Smart\Controllers\Backend;


use Facades\Smart\Service\ServiceManager;
use Smart\Service\MerGoodsCatalogService;
use Illuminate\Http\Request;

class MerGoodsCatalog extends Backend {

    /**
     * MerGoodsCatalog constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = ServiceManager::make(  MerGoodsCatalogService::class);
    }

    //页面入口
    public function index(Request $request) {
        $this->_init( 'MerGoodsCatalog' );

        //uri
        $this->_addParam( 'uri' , [
            'upload'       => full_uri( 'backend/mergoodscatalog/upload' ) ,
            'albumCatalog' => full_uri( 'backend/mergoodscatalog/read_album_catalog' ) ,
            'album'        => full_uri( 'backend/mergoodscatalog/read_album' ) ,


        ] );

        //查询参数
        $this->_addParam( 'query' , [
            'keyword'  => $request->input( 'keyword' , '' ) ,
            'status'   => $request->input( 'status' , '' ) ,
            'page'     => $request->input( 'page' , 1 ) ,
            'pageSize' => $request->input( 'pageSize' , 10 ) ,
            'sort'     => $request->input( 'sort' , 'id' ) ,
            'order'    => $request->input( 'order' , 'DESC' ) ,
        ] );

        //上传参数
        $this->_addParam( 'uploadParam' , [
            'width'       => 300 ,
            'height'      => 300 ,
            'saveAsAlbum' => TRUE ,
            'albumTag'    => '图标' ,
        ] );

//相册参数
        $this->_addParam( 'albumParam' , [
            'defaultTag' => '图标' ,
            'pageSize'   => 12 ,
        ] );

        //其他参数
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow() ,
            'status'     => $this->service->status ,
            'type'       => $this->service->type
        ] );

        //需要引入的 css 和 js


        $this->_addCssLib( 'node_modules/jcrop-0.9.12/css/jquery.Jcrop.min.css' );
        $this->_addJsLib( 'node_modules/jcrop-0.9.12/js/jquery.Jcrop.min.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );


        $this->_addJsLib( 'static/plugins/dmg-ui/TreeGrid.js' );

        return $this->_displayWithLayout('backend::mergoodscatalog.index');
    }


    /**
     * 读取
     * @return \Json
     */
    public function read(Request $request) {
        $config = [
            'status'  => $request->input( 'status' , '' ) ,
            'keyword' => $request->input( 'keyword' , '' ) ,
            'sort'    => $request->input( 'sort' , 'id' ) ,
            'order'   => $request->input( 'order' , 'DESC' ) ,
        ];

        $data['rows'] = $this->service->getByCond( $config );

        return json( ajax_arr( '查询成功' , 0 , $data ) );
    }

}