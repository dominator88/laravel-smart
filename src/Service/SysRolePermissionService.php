<?php
/**
 * SysRolePermission Service
 *
 * @author Zix
 * @version 2.0 2016-05-11
 */

namespace Smart\Service;


use Facades\Smart\Service\ServiceManager;
use Smart\Models\SysRolePermission;
use Illuminate\Support\Facades\DB;
use Smart\Service\SysRoleService;
use Smart\Service\SysFuncPrivilegeService;
use Smart\Service\PermissionService;

class SysRolePermissionService extends BaseService {

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

    protected $model_class = SysRolePermission::class;

    //状态
    public $status = [
        0 => '禁用',
        1 => '启用',
    ];

    

    //根据条件查询
    function getByCond( $param ) {
        $default = [
            'field'    => '',
            'keyword'  => '',
            'status'   => '',
            'page'     => 1,
            'pageSize' => 10,
            'sort'     => 'id',
            'order'    => 'DESC',
            'count'    => FALSE,
            'getAll'   => FALSE
        ];

        $param = extend( $default, $param );

        $model = $this->getModel()->keyword($param['keyword'])->status($param['status']);


        if ( $param['count'] ) {
            return $model->count();
        }

     //   $this->getModel()->field( $param['field'] );
        if ( ! $param['getAll'] ) {
          $model =  $model->getAll($param);
        }


        $data  = $model->orderBy($param['sort'] , $param['order'])->get()->toArray();



        return $data ? $data : [];
    }

    /**
     * 根据角色获取 权限
     *
     * @param $roleId
     *
     * @return array
     */
    public function getPrivilegeByRole( $roleId ) {
        $data = $this->getModel()->where( 'role_id', $roleId )->get()->toArray();

        if ( empty( $data ) ) {
            return $data;
        }

        $newData = [];
        foreach ( $data as $row ) {
            $newData[] = $row['privilege_id'];
        }

        return $newData;
    }

    /**
     * 根据角色获取 授权
     *
     * @param $roleId
     *
     * @return mixed
     */
    function getByRole( $roleId ) {

        //通过原roleid 获取 库roleId
        $sysRoleService = ServiceManager::make(SysRoleService::class);
        $sysRole = $sysRoleService->findById($roleId);
        $permissions = $sysRole->role->permissions->pluck('id');
        $permissionService = ServiceManager::make(PermissionService::class);
        $permissions = $permissionService->getByIds($permissions);
        $data = [];

        foreach($permissions as $permission){
            if(!empty($permission->node->privilege)){
                $data_tmp = [
                    'privilege_id' => $permission->node->privilege->id
                ];
                array_push($data, $data_tmp);
            }
            
        }
        return $data;
     //   return $this->getModel()->where( 'role_id', $roleId )->get()->toArray();
    }

    /**
     * 检查角色权限
     *
     * @param $roleId
     * @param $module
     * @param $func
     * @param $privilege
     *
     * @return array|bool
     */
    function checkRoleFuncPrivilege( $roleId, $module, $func, $privilege ) {
        if ( $roleId == config( 'superAdminId' ) ) {
            return TRUE;
        }

        if ( empty( $privilege ) ) {
            return FALSE;
        }

        $funcUri = "$module/$func/index";

        $data = $this->getModel()
            ->field( 'DISTINCT fp.name' )
            ->alias( 'rp' )
            ->where( 'rp.role_id', 'in', $roleId )
            ->where( 'f.uri', $funcUri )
            ->join( 'sys_func_privilege fp', 'fp.id = rp.privilege_id' )
            ->join( 'sys_func f', 'f.id = fp.func_id' )
            ->select();


        if ( empty( $data ) ) {
            return FALSE;
        }

        $fixData = [];
        foreach ( $data as $row ) {
            $fixData [] = $row ['name'];
        }
        //echo $privilege_name;
        if ( ! in_array( $privilege, $fixData ) ) {
            return FALSE;
        }

        return $fixData;
    }

    /**
     * 根据功能删除
     *
     * @param $funcId
     *
     * @return array
     */
    function destroyByFunc( $funcId ) {
        try {
            $sql = db( 'sys_func_privilege' )->where( 'func_id', $funcId )->buildSql();
            $this->getModel()->where( 'privilege_id', 'in', $sql );
            $this->getModel()->delete();

            return ajax_arr( '删除成功', 0 );
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage(), 500 );
        }
    }

    /**
     * 更新角色授权
     *
     * @param $roleId
     * @param $privilegeArr
     *
     * @return array
     */
    function updateRolePermission( $roleId, $privilegeArr ) {
        $sysRoleService = ServiceManager::make(SysRoleService::class );
        $sysRole = $sysRoleService->findById($roleId);
        $sysFuncPrivilege = ServiceManager::make(SysFuncPrivilegeService::class);
        $result = $sysFuncPrivilege->syncPermissions($roleId,$privilegeArr);
        
        if($result){
            return ajax_arr( '修改权限成功了', 0 );
        }
        return ajax_arr('修改失败', 500);
     
    }

}