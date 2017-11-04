<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 17:40
 */
namespace Smart\Controllers\Backend;

use Smart\Service\SysMerchantService;
use Illuminate\Http\Request;

class SysMerchant extends Backend {

    /**
     * SysMerchant constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = $this->serviceManager->make( SysMerchantService::class);
    }

    //页面入口
    public function index(Request $request) {
        $this->_init( '机构管理' );

        //uri
        $this->_addParam( 'uri' , [
            'area'         => full_uri( 'backend/sysmerchant/read_area' , [ 'pid' => '' ] ) ,
            'upload'       => full_uri( 'backend/sysmerchant/upload' ) ,
            'albumCatalog' => full_uri( 'backend/sysmerchant/read_album_catalog' ) ,
            'album'        => full_uri( 'backend/sysmerchant/read_album' ) ,
            'detail'       => full_uri( 'backend/Sysmerchant/read_detail' , [ 'id' => '' ] )
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
            'forTest'    => $this->service->forTest ,
        ] );

        //需要引入的 css 和 js


        $this->_addCssLib( 'node_modules/jcrop-0.9.12/css/jquery.Jcrop.min.css' );
        $this->_addJsLib( 'node_modules/jcrop-0.9.12/js/jquery.Jcrop.min.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/AreaSelection.js' );

        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );


        return $this->_displayWithLayout('backend::sysmerchant.index');
    }

    /**
     * 读取
     * @return \Json
     */
    function read(Request $request) {
        $config = [
            'status'      => $request->input( 'status' , '' ) ,
            'keyword'     => $request->input( 'keyword' , '' ) ,
            'page'        => $request->input( 'page' , 1 ) ,
            'pageSize'    => $request->input( 'pageSize' , 10 ) ,
            'sort'        => $request->input( 'sort' , 'id' ) ,
            'order'       => $request->input( 'order' , 'DESC' ) ,
            'withSysUser' => TRUE
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return json( ajax_arr( '查询成功' , 0 , $data ) );
    }

    function read_detail($id) {


        $merData = $this->service->getById( $id );

        $this->_init( "机构 " . $merData['name'] . ' 管理页' );
        $this->_addParam( 'uri' , [
            'menu' => '/backend/sysmerchant/index' ,
        ] );

        $this->_addData( 'merId' , $id );
        $this->_addData( 'initPageJs' , FALSE );
        $this->_addJsLib( 'static/js/backend/SysMerchantDetail.js' );
        $this->_addJsCode( 'SysMerchantDetail.init()' );

        return $this->_displayWithLayout( 'backend::sysmerchant.detail' );
    }


}