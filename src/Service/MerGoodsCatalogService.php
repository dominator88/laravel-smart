<?php
/**
 * MerGoodsCatalog Service
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/21
 * Time: 09:35
 */

namespace Smart\Service;



use Smart\Models\MerGoodsCatalog;

class MerGoodsCatalogService extends BaseService {

    //引入 TreeTable trait
    use \Smart\Traits\Service\TreeTable,\Smart\Traits\Service\Instance;

    protected $model_class = MerGoodsCatalog::class;

    public $type = [
        'goods'   => '商品' ,
        'virtual' => '虚拟道具' ,
        'service' => '服务'
    ];

    //状态
    public $status = [
        0 => '禁用' ,
        1 => '启用' ,
    ];

   

    //取默认值
    function getDefaultRow() {
        return [
            'id'     => '' ,
            'mer_id' => '' ,
            'sort'   => '99' ,
            'type'   => 'goods' ,
            'pid'    => '0' ,
            'text'   => '' ,
            'icon'   => '' ,
            'desc'   => '' ,
            'level'  => '1' ,
            'status' => '1' ,
        ];
    }


    //根据条件查询
    public function getByCond( $param ) {
        $default = [
            'field'        => [ '*'] ,
            'keyword'       => '',
            'pid'          => 0 ,
            'merId'        => '' ,
            'status'       => '' ,
            'withTypeText' => FALSE ,
            'key'          => 'children'
        ];
        $param   = extend( $default , $param );
        $model = $this->getModel()->merId($param['merId'])->status($param['status'])->keyword($param['keyword']);


        $data = $model
            ->orderBy( 'level','ASC' )
            ->orderBy( 'sort','ASC')
            ->get($param['field'])->toArray();

        if ( $param['withTypeText'] ) {
            foreach ( $data as &$item ) {
                $item['type_text'] = $this->type[ $item['type'] ];
            }
        }


        $result = [];
        $index  = [];

        foreach ( $data as $row ) {
            if ( $row['pid'] == $param['pid'] ) {
                $result[ $row['id'] ] = $row;
                $index[ $row['id'] ]  = &$result[ $row['id'] ];
            } else {
                $index[ $row['pid'] ][ $param['key'] ][ $row['id'] ] = $row;
                $index[ $row['id'] ]                                 = &$index[ $row['pid'] ][ $param['key'] ][ $row['id'] ];
            }
        }

        return $this->treeToArray( $result , $param['key'] );
    }

}