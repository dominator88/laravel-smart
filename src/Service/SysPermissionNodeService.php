<?php namespace Smart\Service;
/**
 * SysPermissionNode Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2019-05-28
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Smart\Models\SysPermissionNode;
use Smart\Service\BaseService; 
use Smart\Models\Permission;

class SysPermissionNodeService extends BaseService {


  	//引入 TreeTable trait
	use \Smart\Traits\Service\TreeTable;
  	//引入 Instance
	use \Smart\Traits\Service\Instance;

  protected $model_class = SysPermissionNode::class;
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
     'pid' => '' , 
     'level' => '1' , 
     'type' => 'func' , 
     'symbol' => '' , 
     'status' => '1' , 
     'created_at' => '' , 
     'updated_at' => '' , 
   ];
 }

 


  //根据条件查询
 public function getByCond( $param ) {
  $default = [
    'field'  => ['*' ],
    'source_id' => '',
    'type' => '',
    'module' => '',
    'symbol' => '',
    'pid'    => 0,
    'status' => '',
    'count' => 0,
    'key'    => 'children'
  ];

  $func = function (&$arr) use (&$func){
    foreach($arr as &$val){
      if(isset($val['children'])){

        if( empty($val['children'])){
          unset($val['children']);
        }else{
          $func($val['children']);
        }
      }
    }
    return $arr;

  };

  $param  = extend( $default , $param );
  if($param['symbol'] === false){
    return [];
  }

  $data = $this->getModel()->with('children')->sourceId($param['source_id'])->type($param['type'])->module($param['module'])->status($param['status'])->orderBy('level' , 'ASC')->orderBy('sort' , 'ASC')->get($param['field']);
  if ( $param['count'] ) {
    return $data->count();
}
  $data = $func($data);

  return $this->treeToArray( $data, $param['key'] );
}

public function getPrivilege($param){
  $default = [
    'module' => '',
    'symbol' => '',
    'status' => '',
    'type' => '',
    'source_id' => '',
  ];
  $param  = extend( $default , $param );

  return $this->getModel()->sourceId($param['source_id'])->module($param['module'])->status($param['status'])->type($param['type'])->orderBy('sort', 'ASC')->get();
}
  
 
//新增节点即新增权限
public function insert( $data ) {
  try {
    if ( empty( $data ) ) {
      throw new \Exception( '数据不能为空' );
    }
    if(!empty($data['symbol'])){
      $permission = Permission::where('name',$data['symbol'])->first();

      if(empty($permission)){
        $permission = Permission::create(['name'=> $data['symbol'],'guard_name' => 'admin']);
      }else{
        throw new \Exception('插入的权限已存在');
      }

      $data['permission_id'] = $permission->id;
    }else{
      throw new \Exception('插入标记不能为空');
    }
//    $data['level'] = $this->getLevel( $data['pid'] );
    $model            = $this->getModel()->create( $data );

    return ajax_arr( '创建成功', 0, [ 'id' => $model->id ] );
  } catch ( \Exception $e ) {
    return ajax_arr( $e->getMessage(), 500 );
  }
}

/**
  *
   * 根据ID 更新数据
   *
   * @param $id
   * @param $data
   *
   * @return array
   */
public function update( $id, $data ) {
  try {
    /*if ( $data['pid'] == $id ) {
      throw new \Exception( '不能选自己做上级' );
    }*/

    if(!empty($data['symbol'])){
      $permission = Permission::where('name',$data['symbol'])->first();
      if(empty($permission)){
        $permission = Permission::create(['name'=>$data['symbol'],'guard_name'=>'admin']);
        
      }
      $data['permission_id'] = $permission->id;   
    }  

//    $data['level'] = $this->getLevel( $data['pid'] );
    $rows          = $this->getModel()->where( 'id', $id )->update( $data );
  
    if ( $rows == 0 ) {
      return ajax_arr( "未更新任何数据", 0 );
    }

    return ajax_arr( "更新成功", 0 );
  } catch ( \Exception $e ) {
    return ajax_arr( $e->getMessage(), 500 );
  }
}

  /**
   * 根据ID 删除数据
   *
   * @param $ids //string | array
   *
   * @return array
   */
  public function destroy($ids ){
    try{
      $sysPermissionNodes = $this->getModel()->whereIn('id',$ids)->get();
      $sysPermissionNodes->each(function($item){
        if($item->permission){
          $item->permission->delete();
        }
        $item->delete();
      });  
      return true;
    }catch(\Exception $e){
      throw $e;
    }
  }

  public function getByIds($ids){
    return $this->getModel()->whereIn('id',$ids)->get();
  }

  public function getPermissions($nodeIds){
    $permissionNodes = $this->getModel()->whereIn('id',$nodeIds)->get();
    $permissions = collect();
    foreach($permissionNodes as $permissionNode){
            $permissions->push($permissionNode->permission); 
    }

    return $permissions;
}

public function getPermissionBySymbol($symbol){
  return $this->getModel()->where('symbol', $symbol)->first();
}

public function save($param){
  try{
      $param['level'] = $this->getLevel( $param['pid'] );
      if(isset($param['id']) && $param['id']){
        $model = $this->getModel()->findOrFail($param['id']);
   //     unset($param['symbol']);
        $model->update($param);
        return $model;
      }else{
          if(!empty($param['symbol'])){

              $permission = Permission::create(['name'=> $param['symbol'].Str::random(8),'guard_name' => 'admin']);
        
              $param['permission_id'] = $permission->id;
          }else{
              throw new \Exception('权限节点标记不能为空');
          }

        $result = $this->getModel()->create($param);
      }
      return $result;
  }catch(\Exception $e){
      if($e instanceof ModelNotFoundException ){
        throw new \Exception('当前id对应的模型不存在');
      }else{
        throw $e;
      }
      
  }
}



}