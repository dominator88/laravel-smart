<?php namespace Smart\Service;
/**
 * Permission Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2019-04-16
 */

use Smart\Models\Permission;
use Smart\Models\SysUser;
use Smart\Service\BaseService; 

class PermissionService extends BaseService {

  	//引入 GridTable trait
	use \Smart\Traits\Service\GridTable;
  
  	//引入 Instance
	use \Smart\Traits\Service\Instance;

  protected $model_class = Permission::class;
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
    'guard_name' => '',
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

  $model = $this->getModel()->keyword($param['keyword'])->status($param['status'])->guardName($param['guard_name']);

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
      
      $permission = $this->getModel()->create( $data );
      
      return ajax_arr( '创建成功' , 0 , [ 'id' => $permission->id ] );
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
      $rows = $this->getModel()->where( 'id' , $id )->update( $data );
      if ( $rows == 0 ) {
        return ajax_arr( "未更新任何数据" , 0 );
      }
      
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
      $rows = $this->getModel()->destroy( $ids );
      if ( $rows == 0 ) {
        return ajax_arr( "未删除任何数据" , 0 );
      }
      
      return ajax_arr( "成功删除{$rows}行数据" , 0 );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }

  public function getByIds($ids){
    return $this->getModel()->whereIn('id',$ids)->get();
  }


  public function validate($service,$method,SysUser $user){
    if(isset($service->method_permission)){
        $node_name = $service->method_permission[$method];
        if(is_bool($node_name)){
            return $node_name;
        }elseif($node_name == ''){
            return false;
        }
        $sysPermissionNodeService = SysPermissionNodeService::instance();
        $sysUserService = SysUserService::instance();
        $permissionNode = $sysPermissionNodeService->getPermissionBySymbol($node_name);
        $result = $sysUserService->hasPermission($user, $permissionNode);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}
  
}





