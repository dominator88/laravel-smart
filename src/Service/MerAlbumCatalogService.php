<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/26
 * Time: 16:56
 */
namespace Smart\Service;
use Smart\Models\MerAlbumCatalog;
use think\image\Exception;



class MerAlbumCatalogService extends BaseService {
    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

    protected $model_class = MerAlbumCatalog::class;
    //状态
    var $status = [
        0 => '禁用',
        1 => '启用',
    ];

    

    //取默认值
    function getDefaultRow() {
        return [
            'id'         => '',
            'mer_id'     => '',
            'sort'       => '999',
            'text'       => '',
            'icon'       => '',
            'is_default' => 0,
        ];
    }

    //根据条件查询
    function getByCond( $param ) {
        $default = [
            'field'    => ['*'],
            'merId'    => 0,
            'keyword'  => '',
            'status'   => '',
            'page'     => 1,
            'pageSize' => 10,
            'sort'     => 'id',
            'order'    => 'DESC',
            'count'    => FALSE,
            'getAll'   => FALSE,
        ];

        $param = extend( $default, $param );

        $model = $this->getModel()->keyword($param['keyword'])->merId($param['merId']);


        if ( $param['count'] ) {
            return $model->count();
        } else {

           $data = $model->getAll($param)->orderBy($param['sort'], $param['order'])->get( $param['field'])->toArray();

        }

        return $data ? $data : [];
    }

    function getByTag( $mer_id, $tag, $icon = '', $create_when_not_found = FALSE ) {
        $where['tag'] = $tag;
        if ( empty( $mer_id ) ) {
            $where['mer_id'] = [ 'exp', 'is null' ];
        }

        $data = $this->getModel()->where( $where )->first();

        if ( empty( $data ) ) {
            if ( $create_when_not_found ) {
                $new_data = [
                    'mer_id' => $mer_id,
                    'tag'    => $tag,
                    'icon'   => $icon
                ];

                if ( empty( $mer_id ) ) {
                    unset( $new_data['mer_id'] );
                }

                $ret_create = $this->insert( $new_data );
                if ( $ret_create['code'] == 0 ) {
                    $new_data['id'] = $ret_create['data']['id'];

                    return $new_data;
                }
            }

            return FALSE;
        }

        return $data;
    }

    //根据根据多个tag 取分类
    function saveByTags( $mer_id, $tags, $album_id, $icon ) {
        if ( ! is_array( $tags ) ) {
            $tags = explode( ',', trim( $tags ) );
        }

        $new_tag = [];
        foreach ( $tags as $tag ) {
            $tag_data = $this->getByTag( $mer_id, $tag, $icon, TRUE );
            if ( ! $tag_data ) {
                return ajax_arr( '系统繁忙, 请稍后再试', 500 );
            }

            $new_tag[] = [
                'album_id'   => $album_id,
                'catalog_id' => $tag_data['id']
            ];
        }

        if ( empty( $new_tag ) ) {
            return ajax_arr( '没有要添加的数据', 500 );
        }

        $MerAlbumTag = db( 'MerAlbumTag' );
        $ret         = $MerAlbumTag->insertAll( $new_tag );

        if ( $ret === FALSE ) {
            return ajax_arr( '系统繁忙, 请稍后再试', 500 );
        } else {
            return ajax_arr( '保存成功', 0 );
        }
    }

    function destroyOne( $id ) {
        $MerAlbum  = MerAlbumService::instance();
        $albumData = $MerAlbum->getByCond( [
            'catalogId' => $id
        ] );


        try {
            if ( ! empty( $albumData ) ) {
                throw new Exception( '目录下还有图片, 不能删除' );
            }

            $this->getModel()->delete( $id );

            return ajax_arr( '删除成功', 0 );
        } catch ( Exception $e ) {
            return ajax_arr( '删除失败', 500 );
        }
    }


}