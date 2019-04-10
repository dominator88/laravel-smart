<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/21
 * Time: 15:49
 */

namespace Smart\Controllers\Backend;

use Facades\Smart\Service\ServiceManager;
use Smart\Service\SysFuncPrivilegeService;
use Smart\Service\SysFuncService;
use Illuminate\Http\Request;

class MerFunc extends Backend {
    
    /**
     * MerFunc constructor.
     */

    public $autoload_service = 0;

    public $controller = 'SysFunc';

    public function __construct(Request $request){
        parent::__construct($request);
        $this->service = ServiceManager::make( \Smart\Service\SysFuncService::class );

        $jsCode = <<<EOF
            {$this->controller}.init();
EOF;

            $this->_addJsCode($jsCode);
    }

    //页面入口
    public function index(Request $request) {
        $this->_init( '机构功能' );

        //uri
        $this->_addParam( 'uri', [
            'updatePrivilege' => full_uri( 'Backend/MerFunc/update_privilege', [ 'funcId' => '' ] ),
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
        $SysFuncPrivilege = ServiceManager::make( SysFuncPrivilegeService::class );
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow(),
            'module'     => '',
            'status'     => $this->service->status,
            'isMenu'     => $this->service->isMenu,
            'isFunc'     => $this->service->isFunc,
            'privilege'  => $SysFuncPrivilege->name,
            'alias'      => $SysFuncPrivilege->alias,
            'modules'     => $modules,

        ] );

        //需要引入的 css 和 js
        $this->_addJsLib( 'static/plugins/dmg-ui/TreeGrid.js' );

        return $this->_displayWithLayout('backend::merfunc.index');
    }


    /**
     * 读取
     * @return \Json
     */
    function read(Request $request) {
        $module = $request->module ?: explode(',',config('backend.module_ext'));
        
        $config = [
            'module'        => $module ,
            'page'     => $request->input( 'page', 1 ),
            'pageSize' => $request->input( 'pageSize', 10 ),
            'sort'          => $request->input( 'sort', 'id' ),
            'order'         => $request->input( 'order', 'DESC' ),
            'withPrivilege' => TRUE
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return json( ajax_arr( '查询成功', 0, $data ) );
    }

    /**
     * 更新权限
     *
     * @return \Json
     */
    function update_privilege(Request $request , $funcId) {

        $data   = $request->except( '_token' );

        $SysFuncPrivilege = SysFuncPrivilegeService::instance();
        $ret              = $SysFuncPrivilege->updateByFunc( $funcId, $data );

        return json( $ret );
    }

}