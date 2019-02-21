<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/20
 * Time: 13:25
 */

 namespace Smart\Controllers\Backend;

use Smart\Service\MerUserService;
use Illuminate\Http\Request;
use Facades\Smart\Service\ServiceManager;
class MerUser extends Backend {

    /**
     * MerUser constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->merId = 1;
    }

    //页面入口
    public function index(Request $request) {
        $this->_init( '用户管理' );

        //uri
        $this->_addParam( 'uri', [
            'resetPwd'     => full_uri( 'backend/meruser/reset_pwd', [ 'id' => '' ] ),
            'upload'       => full_uri( 'backend/meruser/upload' ),
            'albumCatalog' => full_uri( 'backend/meruser/read_album_catalog' ),
            'album'        => full_uri( 'backend/meruser/read_album' ),
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
        $this->_addParam( 'uploadParam', [
            'merId'       => $this->merId,
            'width'       => 300,
            'height'      => 300,
            'saveAsAlbum' => TRUE,
            'albumTag'    => '头像',
        ] );

        //相册参数
        $this->_addParam( 'albumParam', [
            'merId'      => $this->merId,
            'defaultTag' => '头像',
            'pageSize'   => 12,
        ] );

        //其他参数
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow(),
            'status'     => $this->service->status,
            'regFrom'    => $this->service->regFrom,
            'sex'        => $this->service->sex,
            'resetPwd'   => config( 'backend.defaultPwd' )
        ] );

        //需要引入的 css 和 js
        $this->_addCssLib( 'node_modules/jcrop-0.9.12/css/jquery.Jcrop.min.css' );
        $this->_addJsLib( 'node_modules/jcrop-0.9.12/js/jquery.Jcrop.min.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );

        return $this->_displayWithLayout('backend::meruser.index');
    }


    /**
     * 读取
     * @return Json
     */
    function read(Request  $request) {
        $config = [
            'merId'    => $this->merId,
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

        return json( ajax_arr( '查询成功', 0, $data ) );
    }

    function insert(Request $request) {
        $data = $request->except('_token');

        $data['mer_id'] = $this->merId;
        $result         = $this->service->insert( $data );

        return json( $result );
    }

    function update(Request $request , $id) {
        $data = $request->except('_token');

        $data['mer_id'] = $this->merId;
        $result         = $this->service->update( $id, $data );

        return json( $result );
    }

    function reset_pwd(Request $request , $id) {


        $result = $this->service->resetPwd( $id, config( 'backend.defaultPwd' ) );

        return json( $result );

    }

}