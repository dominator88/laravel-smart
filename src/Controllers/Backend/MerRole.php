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
use Smart\Service\SysRolePermissionService;
use Smart\Service\SysRoleService;
use Illuminate\Http\Request;


class MerRole extends Backend {

    /**
     * SysRole constructor.
     */
    protected $autoload_service = false;

    public $controller = 'SysRole';

    public function __construct(Request $request){
        parent::__construct($request);
        $this->service = ServiceManager::make( \Smart\Service\SysRoleService::class );

    }

    //页面入口
    public function index(Request $request) {
        $this->_init( '机构角色管理' );

        //uri
        $this->_addParam( 'uri', [
            'getPermission'    => full_uri( 'backend/merrole/get_permission' ),
            'getPrivilegeData' => full_uri( 'backend/merrole/get_privilegeData'),
            'updatePermission' => full_uri( 'backend/merrole/update_permission' )
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
            'defaultRow' => $this->service->getDefaultRow(),
            'status'     => $this->service->status,
            'rank'       => $this->service->rank
        ] );

        //需要引入的 css 和 js
        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );


        return $this->_displayWithLayout('backend::merrole.index');
    }

    //读取
    function read(Request $request) {
        $param = [
            'status'   => $request->input( 'status', '' ),
            'keyword'  => $request->input( 'keyword', '' ),
            'page'     => $request->input( 'page', 1 ),
            'pageSize' => $request->input( 'pageSize', 10 ),
            'sort'     => $request->input( 'sort', 'id' ),
            'order'    => $request->input( 'order', 'DESC' ),
            'module'   => 'mp',
        ];

        $data['rows']   = $this->service->getByCond( $param );
        $param['count'] = TRUE;
        $data['total']  = $this->service->getByCond( $param );

        return json( ajax_arr( '查询成功', 0, $data ) );
    }

    /**
     * 新建
     *
     * @return \Json
     */
    public function insert(Request $request) {
        $data = $request->except( '_token' );
        $data['module'] = 'mp';

        return json( $this->service->insert( $data ) );
    }

    function get_permission(Request $request) {
        $roleId = $request->input( 'roleId' );

        $SysFuncPrivilege      = SysFuncPrivilegeService::instance();
        $data['privilegeName'] = $SysFuncPrivilege->name;
        //取角色操作权限
        $SysRolePermission    = SysRolePermissionService::instance();
        $ret['privilegeData'] = $SysRolePermission->getByRole( $roleId );

        //取所有功能与操作
        $SysFunc          = SysFuncService::instance();
        $data['funcData'] = $SysFunc->getByCond( [
            'module'        => 'mp',
            'status'        => 1,
            'withPrivilege' => TRUE,
        ] );

        return view('backend::merrole.permission')->with($data);
    }

    function get_privilegeData(Request $request){
        $roleId = $request->input( 'roleId' );
        $SysRolePermission    = SysRolePermissionService::instance();
        return response()->json($SysRolePermission->getByRole( $roleId ));
    }

    //更新授权
    function update_permission(Request $request) {
        $roleId       = $request->input( 'roleId' );
        $privilegeArr = $request->input( 'privilegeArr' );

        $SysRolePermission = SysRolePermissionService::instance();
        $ret               = $SysRolePermission->updateRolePermission( $roleId, $privilegeArr );

        return json( $ret );
    }


}