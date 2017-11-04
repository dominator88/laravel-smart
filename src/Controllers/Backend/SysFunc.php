<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 10:18
 */
namespace Smart\Controllers\Backend;

use Smart\Service\SysFuncPrivilegeService;
use Smart\Service\SysFuncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SysFunc extends Backend{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = $this->serviceManager->make( SysFuncService::class);
    }

    public function index(Request $request){
        $this->_init( '系统功能' );

        //uri
        $this->_addParam( 'uri', [
            'updatePrivilege' => full_uri( 'Backend/SysFunc/update_privilege', [ 'funcId' => '' ] ),
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
        $SysFuncPrivilege = SysFuncPrivilegeService::instance();
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow(),
            'status'     => $this->service->status,
            'isMenu'     => $this->service->isMenu,
            'isFunc'     => $this->service->isFunc,
            'privilege'  => $SysFuncPrivilege->name,
            'alias'      => $SysFuncPrivilege->alias
        ] );

        //需要引入的 css 和 js
        $this->_addJsLib( 'static/plugins/dmg-ui/TreeGrid.js' );

      //  var_dump($request->server());exit;
       return $this->_displayWithLayout('backend::sysfunc/index');

    }

    /**
     * 读取
     */
    function read(Request $request) {
        $config = [
            'status'        => $request->input( 'status', '' ),
            'keyword'       => $request->input( 'keyword', '' ),
            'sort'          => $request->input( 'sort', 'id' ),
            'order'         => $request->input( 'order', 'DESC' ),
            'withPrivilege' => TRUE
        ];

        $data['rows'] = $this->service->getByCond( $config );

        return response()->json( ajax_arr( '查询成功', 0, $data ) );
    }


    /**
     * 更新权限
     *
     */
    function update_privilege(Request $request) {
        $funcId = $request->funcId;
        $data   = $request->all();

        $SysFuncPrivilege = SysFuncPrivilegeService::instance();
        $ret              = $SysFuncPrivilege->updateByFunc( $funcId, $data );

        return response()->json( $ret );
    }


}