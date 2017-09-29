<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 15:34
 */
namespace Smart\Service;



use Smart\Models\SysArea;

class SysAreaService extends BaseService {

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
            self::$instance        = new SysAreaService();
            self::$instance->setModel(new SysArea()) ;
        }

        return self::$instance;
    }

    //取默认值
    function getDefaultRow() {
        return [
            'id'     => '',
            'pid'    => '',
            'text'   => '',
            'tip'    => '',
            'status' => '0',
            'level'  => '0',
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
            'field'    => [],
            'keyword'  => '',
            'pid'      => '',
            'status'   => '',
            'page'     => 1,
            'pageSize' => 10,
            'sort'     => 'id',
            'order'    => 'ASC',
            'count'    => FALSE,
            'getAll'   => FALSE
        ];

        $param = extend( $default, $param );
        $model = $this->getModel()->keyword( $param['keyword'])->status($param['status'])->pid($param['pid'])->getAll($param);


        if ( $param['count'] ) {
            return $model->count();
        }


        $data =  $model->orderBy( $param['sort'] ,  $param['order'])->get()->toArray();


        return $data ? $data : [];
    }

}