<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/22
 * Time: 16:59
 */
namespace Smart\Service;


use Smart\Models\SysAppVersion;

class SysAppVersionService extends BaseService {

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

    protected $model_class = SysAppVersion::class;

    public $device = [
        'ios'     => 'iOS' ,
        'android' => '安卓'
    ];

    //状态
    public $status = [
        0 => '禁用' ,
        1 => '启用' ,
    ];

    

    //取默认值
    function getDefaultRow() {
        return [
            'id'              => '' ,
            'device'          => 'android' ,
            'version'         => '' ,
            'uri'             => '' ,
            'description'     => '' ,
            'is_force_update' => '0' ,
            'environment'     => 'production' ,
            'status'          => '1' ,
            'created_at'      => date( 'Y-m-d H:i:s' ) ,
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
            'field'    => [ '*'] ,
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
        $model = $this->getModel()->keyword($param['keyword'])->status($param['status']);


        if ( $param['count'] ) {
            return $model->count();
        }



        $data = $model->getAll($param)->orderBy($param['sort'] , $param['order'] )->get($param['field'])->toArray();





        return $data ? $data : [];
    }

}