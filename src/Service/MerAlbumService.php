<?php namespace Smart\Service;
/**
 * MerAlbum Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2017-09-26
 */

use Smart\Models\MerAlbum;

class MerAlbumService extends BaseService {

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
			self::$instance = new MerAlbumService();
			self::$instance->setModel(new MerAlbum());
		}
		return self::$instance;
	}

  //取默认值
	function getDefaultRow() {
		return [
			'id' => '' , 
'mer_id' => '' , 
'sort' => '999' , 
'uri' => '' , 
'size' => '' , 
'mimes' => '' , 
'img_size' => '' , 
'desc' => '' , 
'status' => '1' , 
'created_at' => date('Y-m-d H:i:s') , 
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
    'field'    => [ '*'],
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


   $data =  $model->getAll($param)->orderBy($param['sort'], $param['order'])->get($param['field'])->toArray();

  return $data ? $data : [ ];
}
  
}