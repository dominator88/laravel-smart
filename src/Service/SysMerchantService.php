<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 17:43
 */
 namespace Smart\Service;



use Smart\Models\SysMerchant;

class SysMerchantService extends BaseService {

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

    protected $model_class = SysMerchant::class;

    public $forTest = [
        0 => '否' ,
        1 => '是'
    ];

    //状态
    public $status = [
        0 => '禁用',
        1 => '启用',
    ];

    

    //取默认值
    function getDefaultRow() {
        return [
            'id'              => '',
            'sort'            => '999',
            'name'            => '',
            'icon'            => '',
            'phone'           => '',
            'contact'         => '',
            'email'           => '',
            'id_card'         => '',
            'status'          => '0',
            'area'            => '',
            'address'         => '',
            'settled_amount'  => '0.00',
            'balance'         => '0.00',
            'withdraw_amount' => '0.00',
            'create_time'     => date( 'Y-m-d H:i:s' ),
            'apply_user_id'   => '',
            'for_test'        => '0',
        ];
    }

    /**
     * 根据条件查询
     *
     * @param $param
     *
     * @return array|number
     */
    function getByCond( $param ) {
        $default = [
            'field'       => [],
            'keyword'     => '',
            'status'      => '',
            'page'        => 1,
            'pageSize'    => 10,
            'sort'        => 'id',
            'order'       => 'DESC',
            'count'       => FALSE,
            'getAll'      => FALSE,
            'withSysUser' => FALSE
        ];

        $param = extend( $default, $param );
        $model = $this->getModel()->keyword($param['keyword'])->status();


        if ( $param['count'] ) {
            return $model->count();
        }

        $param['field'] = ['*' ];

        $data = $model->getAll($param)->orderBy( $param['sort'] , $param['order'])->get($param['field'])->toArray();


        if ( $param['withSysUser'] ) {
            $data = $this->withSysUser( $data );
        }


        return $data ? $data : [];
    }

    /**
     * 查询 商户的管理用户
     * @param $data
     *
     * @return mixed
     */
    private function withSysUser( $data ) {
        if ( empty( $data ) ) {
            return $data ;
        }
        $merIds = [];
        foreach ( $data as $item ) {
            $merIds[] = $item['id'];
        }

        $SysMerchant = SysMerchantService::instance();
        $sysMerchantData =  $SysMerchant->getModel()->whereIn('id' ,$merIds);

        $newUserData = [];
        foreach($sysMerchantData as $sm){
            $tmp_user = $sm->sysUsers()->toArray();
            foreach($tmp_user as $u){
                 $newUserData[$sm->mer_id][] =  $u;
            }
        }


       /* foreach ( $userData as $item ) {
            $newUserData[$item['mer_id']][] = $item ;
        }*/

        foreach ( $data as &$row ) {
            if( isset( $newUserData[$row['id']] ) ) {
                $row['sys_user'] = $newUserData[$row['id']] ;
            } else {
                $row['sys_user'] = '';
            }
        }

        return $data ;
    }

    /**
     * 查询测试商户
     * @return mixed
     */
    public function getForTest() {
        return $this->getModel()->where( 'for_test', 1 )->select();
    }

    /**
     * 根据管理员查询 商户
     *
     * @param $userId
     *
     * @return array
     */
    public function getBySysUser( $userId ) {
        $data = DB::table( 'MerSysUser as su' )
            ->where( 'su.sys_user_id', $userId )
            ->leftJoin( 'sys_merchant as m', 'm.id = su.mer_id' )
            ->first();

        return $data ? $data : [];
    }

}