<?php namespace Smart\Service;
/**
 * SysFuncExtend Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2019-09-04
 */

use Smart\Models\SysFuncExtend;
use Smart\Service\BaseService; 

class SysFuncExtendService extends BaseService {

  	//引入 GridTable trait
	use \Smart\Traits\Service\GridTable;
  
  	//引入 Instance
	use \Smart\Traits\Service\Instance;

  protected $model_class = SysFuncExtend::class;
  //状态
	public $status = [
		0 => '禁用',
		1 => '启用',
	];

  

  //取默认值
	function getDefaultRow() {
		return [
			'id' => '' , 
'func_id' => '' , 
'extend_name' => '' , 
'extend_path' => '' , 
'extend_component' => '' , 
'extend_notCache' => '0' , 
'extend_showAlways' => '0' , 
'created_at' => '' , 
'updated_at' => '' , 
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
  if(!$param['getAll']){
    $data = $model->get()->forPage($param['page'] , $param['pageSize'])->values();
  }else{
    $data = $model->get();
  }
  return $data;
}
  


  public function updateOrCreate($data ,$id = 0){

      return $this->getModel()->updateOrCreate(['func_id' => $id], $data);
    
  }
  
}