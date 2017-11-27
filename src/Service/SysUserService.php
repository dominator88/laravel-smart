<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/16
 * Time: 13:34
 */


namespace Smart\Service;

use Smart\Models\SysFunc;
use Smart\Models\SysUser;
use Smart\Models\SysUserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class SysUserService extends BaseService {

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable;

    //状态
    public $status = [
        0 => '禁用' ,
        1 => '启用' ,
    ];

    //类实例
    private static $instance;

    //生成类单例
    public static function instance() {
        if ( self::$instance == NULL ) {
            self::$instance        = new SysUserService();
            self::$instance->setModel(new SysUser())  ;

        }

        return self::$instance;
    }

    public $user = null;

    /**
     * 取默认值
     *
     * @return array
     */
    function getDefaultRow() {
        return [
            'id'         => '' ,
            'module'     => 'backend' ,
            'username'   => '' ,
            'password'   => '' ,
            'icon'       => '' ,
            'email'      => '' ,
            'phone'      => '' ,
            'status'     => '1' ,
            'token'      => '' ,
            'created_at' => date( 'Y-m-d H:i:s' )
        ];
    }

    /**
     * 根据条件查询
     *
     * @param $params
     *
     * @return array|number
     */
    public function getByCond( $params ) {

        $default = [
            'field'     => [ '*' ] ,
            'module'    => 'backend' ,
            'keyword'   => '' ,
            'status'    => '' ,
            'merId'     => '' ,
            'page'      => 1 ,
            'pageSize'  => 10 ,
            'sort'      => 'id' ,
            'order'     => 'DESC' ,
            'getAll'    => FALSE ,
            'count'     => FALSE ,
            'withPwd'   => FALSE ,
            'withRoles' => FALSE ,
            'merchant'  => FALSE
        ];

        $params = extend( $default , $params );

        if ( $params['merchant'] ) {
            return $this->getMerSysUserByCond( $params );
        }

        $model = $this->getModel()->status($params['status'])->module($params['module'])->keyword($params['keyword']);




        if ( $params['count'] ) {
            return $model->count();
        } else {
            $data =  $model
                 ->orderBy( $params['sort'] ,  $params['order'])->get()->toArray();

        }


        if ( ! $params['withPwd'] ) {
            foreach ( $data as &$item ) {
                unset( $item['password'] );
            }
        }

        if ( $params['withRoles'] ) {
            $data = $this->getRoles( $data );
        }

        return $data ? $data : [];
    }

    /**
     * todo 需要优化
     *
     * @param $data
     *
     * @return mixed
     */
    private function getRoles( $data ) {
        $SysUserRole = SysUserRoleService::instance();

        foreach ( $data as &$item ) {
            $item['roles'] = $SysUserRole->getByUser( $item['id'] );
        }

        return $data;
    }

    /**
     * 获取 MP 平台用户
     *
     * @param $params
     *
     * @return array
     */
    private function getMerSysUserByCond( $params ) {
        $sysMerchant = SysMerchantService::instance();
        $model = $sysMerchant->getModel()->find($params['merId'])->sysUsers()->status($params['status']);

        if ( $params['count'] ) {
            return $model->count();
        } else {
        //    $model->field( 'u.*' );
            $data = $model->getAll($params)->orderBy($params['sort'] ,$params['order'])->get()->toArray();

        }

        if ( ! $params['withPwd'] ) {
            foreach ( $data as &$item ) {
                unset( $item['password'] );
            }
        }

        if ( $params['withRoles'] ) {
            $data = $this->getRoles( $data );
        }

        return $data ? $data : [];

    }

    /**
     * 更新密码
     *
     * @param $id
     * @param $data
     *
     * @return array
     */
    function uploadPwd( $id , $data ) {
        try {
            $this->getModel()->where( 'id' , $id )->update( $data );

            return ajax_arr( '更新成功' , 0 );
        } catch ( \Exception $e ) {

            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    /**
     * 添加数据
     *
     * @param $data
     *
     * @return array
     */
    public function insert( $data ) {
        DB::beginTransaction();
        try {
            if ( empty( $data ) ) {
                throw new \Exception( '数据不能为空' );
            }

            $roles = isset( $data['roles'] ) ? $data['roles'] : [];
            unset( $data['roles'] );
            $data['password'] = str2pwd( config( 'defaultPwd' ) );

            $id = $this->getModel()->insertGetId( $data );
            if ( $id <= 0 ) {
                throw new \Exception( '创建用户失败' );
            }

            //更新用户角色
            if ( ! empty( $roles ) ) {
                $SysUserRole = SysUserRoleService::instance();
                $RoleResult  = $SysUserRole->updateByUser( $id , $roles );
                if ( $RoleResult['code'] > 0 ) {
                    throw new \Exception( $RoleResult['msg'] );
                }
            }

            DB::commit();

            return ajax_arr( '创建用户成功' , 0 , [ 'id' => $id ] );
        } catch ( \Exception $e ) {
            DB::rollback();

            return ajax_arr( $e->getMessage() , 500 );
        }
    }


    //更新
    function update( $id , $data ) {
        DB::beginTransaction();
        try {
            $roles = [];
            if ( isset( $data['roles'] ) ) {
                $roles = $data['roles'];
            }

            unset( $data['roles'] );
            $ret = $this->getModel()->where( 'id' , $id )->update( $data );

            //更新用户角色
            $SysUserRole = SysUserRoleService::instance();
            $RoleResult  = $SysUserRole->updateByUser( $id , $roles );
            if ( $RoleResult['code'] > 0 ) {
                throw new \Exception( $RoleResult['msg'] );
            }

            DB::commit();

            return ajax_arr( '更新成功' , 0 );
        } catch ( \Exception $e ) {
            DB::rollback();

            //echo $this->getModel()->getLastSql();
            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    /**
     * 删除系统用户
     *
     * @param $id
     *
     * @return array
     */
    public function destroy( $id ) {
        $model = $this->getModel();
        try {
            if ( $id <= 2 ) {
                throw new \Exception( '系统用户不能删除' );
            }
            $model_user_role = new SysUserRole();
            //删除用户角色
            $model_user_role->destroy($id);

            //删除用户
            $model->destroy($id);

            return ajax_arr( '删除成功' , 0 );
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    /**
     * 重置密码
     *
     * @param $id
     * @param $pwd
     *
     * @return array
     */
    public function resetPwd( $id , $pwd ) {
        try {
          //  $data['password'] = str2pwd( $pwd );
            $data['password'] = Hash::make($pwd);
            $row              = $this->getModel()->where( 'id' , $id )->update( $data );
            if ( $row <= 0 ) {
                return ajax_arr( '未修改任何记录' , 500 );
            }

            return ajax_arr( '重置密码成功' , 0 );
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    public function setUser($id){
        $this->user = SysUser::find($id);
    }

    public function visitSysFuncService(  SysFuncService $sysFuncService ){
        if($this->user->id == config('backend.superAdminId')){
            return true;
        }
        $roles = $this->user->sysRole->toArray();
        $roles = array_column($roles , 'id' );
        $sysFuncs = SysFunc::whereIn( 'id' , $roles)->get(['uri'])->toArray();
        $sysFuncs = array_column($sysFuncs , 'uri');
        if( in_array( $sysFuncService->privilege->uri , $sysFuncs)){
            return true;
        }
        return false;
    }


}