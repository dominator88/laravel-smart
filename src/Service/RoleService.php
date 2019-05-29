<?php namespace Smart\Service;
/**
 * Role Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2019-04-16
 */

use Smart\Models\Role;
use Smart\Service\BaseService; 

class RoleService extends BaseService {

  	//引入 GridTable trait
	use \Smart\Traits\Service\GridTable;
  
  	//引入 Instance
	use \Smart\Traits\Service\Instance;

  protected $model_class = Role::class;
  //状态
	public $status = [
		0 => '禁用',
		1 => '启用',
	];

  

  //取默认值
	function getDefaultRow() {
		return [
			'id' => '' , 
'name' => '' , 
'guard_name' => '' , 
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

  $model = $this->getModel()->with('permissions')->keyword($param['keyword'])->status($param['status']);

  if ( $param['count'] ) {
    return $model->count();
  }
  if($param['getAll'] === FALSE){
    $model = $model->get()->forPage($param['page'] , $param['pageSize'])->values();
  }else{
    $model = $model->get();
  }
  $data = $model->toArray();


  return $data ? $data : [ ];
}

public function insert( $data ) {
    try {
      if ( empty( $data ) ) {
        throw new \Exception( '数据不能为空' );
      }
      $role = [
        'name' => $data['name'],
        'guard_name' => $data['guard_name'],
      ];
      $role = $this->getModel()->create( $role );

      isset($data['permission']) && $role->syncPermissions($data['permission']);
      
      return ajax_arr( '创建成功' , 0 , [ 'id' => $role->id ] );
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
    //  $rows = $this->getModel()->where( 'id' , $id )->update( $data );
      $role = Role::find($id);

      isset($data['permission']) &&  $role->syncPermissions($permissions);
      
      return ajax_arr( "更新成功" , 0 );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }
  
  /**
   * 根据ID 删除数据
   *
   * @param $ids //string | array
   *
   * @return array
   */
  public function destroy( $ids ) {
    try {
    //  $rows = $this->getModel()->destroy( $ids );
      $roles = $this->getModel()->whereIn('id',(array)$ids)->get();
      
      foreach($roles as $role){
        $role->syncPermissions([]);
      }
      $rows = $this->getModel()->destroy( $ids );
      if ( $rows == 0 ) {
        return ajax_arr( "未删除任何数据" , 0 );
      }
      
      return ajax_arr( "成功删除{$rows}行数据" , 0 );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }

  public function getByGuard($guard = 'admin'){
    $roles = $this->getModel()->where('guard_name','admin')->get();

    return $roles;
  }

  
  
}