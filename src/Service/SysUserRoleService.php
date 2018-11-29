<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/16
 * Time: 14:05
 */

namespace Smart\Service;

use Smart\Models\SysUserRole;
use Illuminate\Support\Facades\DB;

class SysUserRoleService extends BaseService {
    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

    protected $model_class = SysUserRole::class;
    //状态
    public $status = [
        0 => '禁用' ,
        1 => '启用' ,
    ];

    


    /**
     * 取默认值
     *
     * @return array
     */
    public function getDefaultRow() {
        return [
            'id'      => '' ,
            'user_id' => '' ,
            'role_id' => '' ,
        ];
    }

    /**
     * 根据条件查询
     *
     * @param $param
     *
     * @return array
     */
    public function getByCond( $param ) {
        $default = [
            'field'    => '' ,
            'keyword'  => '' ,
            'status'   => '' ,
            'page'     => 1 ,
            'pageSize' => 10 ,
            'sort'     => 'id' ,
            'order'    => 'DESC' ,
            'count'    => FALSE ,
            'getAll'   => FALSE ,
        ];

        $param = extend( $default , $param );

        $model = $this->name($param['keyword'])->status($param['status']);


        if ( $param['count'] ) {
            return $this->getModel()->count();
        } else {
            //$this->getModel()->field( $param['field'] );

            $data = $model->getAll($param)->orderBy( $param['sort'] , $param['order'])->get()->toArray();

        }

        return $data ? $data : [];
    }

    /**
     * 根据用户取角色
     *
     * @param $userId
     * @param bool $concatRole
     *
     * @return array
     */
    public function getByUser( $userId , $concatRole = FALSE ) {

            $model = DB::table('sys_user_role')
            ->join('sys_role', 'sys_role.id' , '=' , 'sys_user_role.role_id' )
            ->where( 'sys_user_role.user_id' , $userId );

        if ( $concatRole ) {
            $data = $model->first([
                'GROUP_CONCAT( sys_role.role_id ) AS role_id' ,
                'GROUP_CONCAT( sys_role.name ) AS role_name' ,
                'MAX(sys_role.rank) AS role_rank'
            ]);
        } else {

            $data = $model->get( [
                'sys_user_role.*' ,
                'sys_role.name as role_name' ,
                'sys_role.rank as role_rank'
            ] )->toArray();
        }

        return $data ? $data : [];
    }

    /**
     * 删除用户角色
     *
     * @param $userId
     *
     * @return array
     */
    public function destroyByUser( $userId ) {
        try {
            $this->getModel()->where( 'user_id' , $userId )->delete();

            return ajax_arr( '删除成功' , 0 );
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    /**
     * 更新用户角色
     *
     * @param $userId
     * @param array $roles
     *
     * @return array
     */
    public function updateByUser( $userId , $roles = [] ) {
        if ( $userId == config( 'superAdminId' ) ) {
            return ajax_arr( '修改成功' , 0 );
        }

        //查询老数据
        $oldData = $this->getByUser( $userId );

        $oldRoles = [];
        foreach ( $oldData as $row ) {
            $oldRoles[] = $row->role_id;
        }

        //查询差值
        $needDelete = array_diff( $oldRoles , $roles );
        $needAdd    = array_diff( $roles , $oldRoles );

        if ( ! empty( $needDelete ) ) {
            //删除取消的角色
             $this->getModel()->where( 'user_id' , $userId )->whereIn( 'role_id' ,  $needDelete )->delete();

        }

        if ( ! empty( $needAdd ) ) {
            //添加新加的角色
            $data = [];
            foreach ( $needAdd as $role ) {
                $data[] = [
                    'user_id' => $userId ,
                    'role_id' => $role
                ];
            }

            $this->getModel()->insert( $data );
        }

        return ajax_arr( '更新成功' , 0 );

    }
}