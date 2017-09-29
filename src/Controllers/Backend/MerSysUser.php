<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/21
 * Time: 13:31
 */

namespace Smart\Controllers\Backend;

use Smart\Service\MerSysUserService;
use Smart\Service\SysRoleService;
use Smart\Service\SysUserService;
use Illuminate\Http\Request;


class MerSysUser extends Backend {

    /**
     * SysMerUser constructor.
     */
    public function __construct(Request  $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = SysUserService::instance();
    }

    /**
     * 页面显示接口
     *
     * @return string
     */
    public function index(Request $request , $merId = 0) {
        $merId = $merId ?: $request->input('merId');
        $this->_init( '系统用户' );

        $this->_addParam( 'uri' , [
            'menu'         => '/backend/sysmerchant/index' ,
            'insert'       => full_uri( 'backend/mersysuser/insert' , [ 'merId' => $merId ] ) ,
            'destroy'      => full_uri( 'backend/mersysuser/destroy' , [ 'merId' => $merId ] ) ,
            'upload'       => full_uri( 'backend/mersysuser/upload' ) ,
            'resetPwd'     => full_uri( 'backend/mersysuser/reset_pwd' , [ 'id' => '' ] ) ,
            'albumCatalog' => full_uri( 'backend/mersysuser/read_album_catalog' ) ,
            'album'        => full_uri( 'backend/mersysuser/read_album' ) ,
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
            'merId'    => $merId ,
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
            'roles'      => $SysRole->getByModule( 'mp' )
        ] );

        $this->_addCssLib( 'node_modules/jcrop-0.9.12/css/jquery.Jcrop.min.css' );
        $this->_addJsLib( 'node_modules/jcrop-0.9.12/js/jquery.Jcrop.min.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/Uploader.js' );
        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );

        return $this->_displayWithLayout('mersysuser.index');
    }

    /**
     * 读取
     */
    public function read(Request $request) {
        $config = [
            'module'    => 'mp' ,
            'merId'     => $request->input( 'merId' , '' ) ,
            'keyword'   => $request->input( 'keyword' , '' ) ,
            'status'    => $request->input( 'status' , '' ) ,
            'page'      => $request->input( 'page' , 1 ) ,
            'pageSize'  => $request->input( 'pageSize' , 10 ) ,
            'withRoles' => TRUE ,
            'merchant'  => TRUE
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return json( ajax_arr( '查询成功' , 0 , $data ) );
    }

    public function insert(Request $request) {
        $merId          = $request->route('merId');
        $data           = $request->except( '_token' );
        $data['module'] = 'mp';
        $MerSysUser     = MerSysUserService::instance();
        $result         = $MerSysUser->insert( $merId , $data );

        return json( $result );
    }


    public function update(Request $request , $id) {

        $data = $request->except( '_token' );

        $MerSysUser = MerSysUserService::instance();
        $result     = $MerSysUser->update( $id , $data );

        return json( $result );
    }

    public function destroy(Request $request) {
        $merId = $request->route( 'merId' );
        $ids   = $request->input( 'ids' );

        $MerSysUser = MerSysUserService::instance();
        $result     = $MerSysUser->destroy( $merId , $ids );

        return json( $result );
    }

    /**
     * 重置密码
     *
     * @return Json
     */
    public function reset_pwd(Request $request , $id) {

        $result = $this->service->resetPwd( $id , config( 'backend.defaultPwd' ) );

        return json( $result );
    }
}
