<?php
/**
 * SysRolePermission Service
 *
 * @author Zix
 * @version 2.0 2016-05-11
 */

namespace Smart\Service;



use Smart\Models\SysRolePermission;
use Illuminate\Support\Facades\DB;

class SysRolePermissionService extends BaseService {

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable;

    //状态
    public $status = [
        0 => '禁用',
        1 => '启用',
    ];

    //类实例
    private static $instance;

    //生成类单例
    public static function instance() {
        if ( self::$instance == NULL ) {
            self::$instance        = new SysRolePermissionService();
            self::$instance->setModel(new SysRolePermission());
        }

        return self::$instance;
    }

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
        return $this->getModel()->where( 'role_id', $roleId )->get()->toArray();
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

        DB::beginTransaction();
        try {
            $oldPrivilegeData = $this->getPrivilegeByRole( $roleId );

            $needAdd    = array_diff( $privilegeArr, $oldPrivilegeData );
            $needDelete = array_diff( $oldPrivilegeData, $privilegeArr );

            if ( ! empty( $needDelete ) ) {
                $this->getModel()
                    ->where( 'role_id', $roleId )
                    ->whereIn( 'privilege_id',  $needDelete )
                    ->delete();
             //   echo $this->getModel()->toSql();
            }
            if ( ! empty( $needAdd ) ) {
                $addData = [];
                foreach ( $needAdd as $privilegeId ) {
                    $addData[] = [
                        'role_id'      => $roleId,
                        'privilege_id' => $privilegeId
                    ];
                }
                $this->getModel()->insert( $addData );
            }

            DB::commit();

            return ajax_arr( '修改权限成功了', 0 );
        } catch ( \Exception $e ) {
            DB::rollback();

            return ajax_arr( $e->getMessage() . '--- here', 500 );
        }
    }

}