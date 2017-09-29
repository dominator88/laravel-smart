<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/21
 * Time: 11:22
 */

namespace Smart\Service;



use Smart\Models\MerSysUser;

class MerSysUserService extends BaseService {

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
            self::$instance        = new MerSysUserService();
            self::$instance->setModel(new MerSysUser()) ;
        }

        return self::$instance;
    }

    //取默认值
    function getDefaultRow() {
        return [
            'id'          => '' ,
            'mer_id'      => '' ,
            'sys_user_id' => '' ,
        ];
    }

    /**
     * 根据条件查询
     *
     * @param $param
     *
     * @return array|number
     */
    public function getByCond( $param ) {
        $default = [
            'field'    => [] ,
            'keyword'  => '' ,
            'status'   => '' ,
            'page'     => 1 ,
            'pageSize' => 10 ,
            'sort'     => 'id' ,
            'order'    => 'DESC' ,
            'count'    => FALSE ,
            'getAll'   => FALSE
        ];

        $param = extend( $default , $param );
        $model = $this->getModel()->keyword($param['keyword'])->status();


        if ( $param['count'] ) {
            return $model->count();
        }

        $data = $model->getAll($param)->get($param['field'])->orderBy($param['sort'] , $param['order'])->get($param['field'])->toArray();
        

        return $data ? $data : [];
    }

    /**
     * 新增机构管理员
     *
     * @param $merId
     * @param $data
     *
     * @return array
     */
    function insert( $merId , $data ) {
        try {
            $SysUser = SysUserService::instance();
            $result  = $SysUser->insert( $data );

            if ( $result['code'] != 0 ) {
                throw new \Exception( $result['msg'] );
            }

            $newData = [
                'mer_id'      => $merId ,
                'sys_user_id' => $result['data']['id']
            ];

            $this->getModel()->insert( $newData );


            return ajax_arr( '创建系统管理用户成功' , 0 );
        } catch ( \Exception $e ) {

            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    /**
     * 根据ID 更新数据
     *
     * @param $id
     * @param $data
     *
     * @return array
     */
    public function update( $id , $data ) {
        try {

            $SysUser = SysUserService::instance();
            $result  = $SysUser->update( $id , $data );

            if ( $result['code'] != 0 ) {
                throw new \Exception( $result['msg'] );
            }

            return $result;
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage() , 500 );
        }
    }

    /**
     * 删除机构管理用户
     *
     * @param $merId
     * @param $sys_user_id
     *
     * @return array
     */
    public function destroy( $merId , $sys_user_id ) {
        try {
            $this->getModel()
                ->where( 'mer_id' , $merId )
                ->where( 'sys_user_id' , $sys_user_id )
                ->delete();

            $SysUser = SysUserService::instance();
            $result  = $SysUser->destroy( $sys_user_id );

            if ( $result['code'] != 0 ) {
                throw new \Exception( $result['msg'] );
            }

            return $result;
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage() , 500 );
        }
    }

}