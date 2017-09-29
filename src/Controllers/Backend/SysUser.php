<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/16
 * Time: 13:31
 */

namespace Smart\Controllers\Backend;

use Smart\Service\SysRoleService;
use Smart\Service\SysUserService;
use Illuminate\Http\Request;

class SysUser extends Backend {

    /**
     * SysUser constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = SysUserService::instance();
    }

    /**
     * 页面显示接口
     *
     * @return string
     */
    public function index(Request $request) {
        $this->_init( '系统用户' );

        $this->_addParam( 'uri' , [
            'upload'       => full_uri( 'backend/sysuser/upload' ) ,
            'resetPwd'     => full_uri( 'backend/sysuser/reset_pwd' , [ 'id' => '' ] ) ,
            'albumCatalog' => full_uri( 'backend/sysuser/read_album_catalog' ) ,
            'album'        => full_uri( 'backend/sysuser/read_album' ) ,
        ] );

        //上传参数
        $this->_addParam( 'uploadParam' , [
            'width'       => 300 ,
            'height'      => 300 ,
            'saveAsAlbum' => TRUE ,
            'albumTag'    => '头像' ,
        ] );

        //相册参数
        $this->_addParam( 'albumParam' , [
            'defaultTag' => '头像' ,
            'pageSize'   => 12 ,
        ] );

        //查询参数
        $this->_addParam( 'query' , [
            'keyword'  => $request->input( 'keyword' , '' ) ,
            'status'   => $request->input( 'status' , '' ) ,
            'page'     => $request->input( 'page' , 1 ) ,
            'pageSize' => $request->input( 'pageSize' , 10 ) ,
        ] );

        $SysRole = SysRoleService::instance();
        //附加参数
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow() ,
            'defaultPwd' => config( 'backend.defaultPwd' ) ,
            'status'     => $this->service->status ,
           'roles'      => $SysRole->getByModule( $this->module )
        ] );

        $this->_addCssLib( 'node_modules/jcrop-0.9.12/css/jquery.Jcrop.min.css' );
        $this->_addJsLib( 'node_modules/jcrop-0.9.12/js/jquery.Jcrop.min.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );

        return $this->_displayWithLayout('backend::sysuser/index');
    }

    /**
     * 读取
     */
    public function read(Request $request) {
        $config = [
            'module'    => 'backend' ,
            'keyword'   => $request->input( 'keyword' , '' ) ,
            'status'    => $request->input( 'status' , '' ) ,
            'page'      => $request->input( 'page' , 1 ) ,
            'pageSize'  => $request->input( 'pageSize' , 10 ) ,
            'withRoles' => TRUE
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return response()->json( ajax_arr( '查询成功' , 0 , $data ) );
    }

    /**
     * 重置密码
     *
     * @return json
     */
    public function reset_pwd($id) {

        $result = $this->service->resetPwd( $id , config( 'backend.defaultPwd' ) );

        return response()->json( $result );
    }
}
