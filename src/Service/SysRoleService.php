<?php
/**
 * SysRole Service
 *
 * @author MR.Z << zsh2088@gmail.com >>
 * @version 2.0 2017-09-15
 */

namespace Smart\Service;

use Smart\Models\SysRole;
use Facades\Smart\Service\ServiceManager;
use Smart\Models\Role;

class SysRoleService extends BaseService {

	//引入 GridTable trait
	use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

	protected $model_class = SysRole::class;
	//状态
	var $status = [
		0 => '禁用',
		1 => '启用',
	];

	var $rank = [
		1 => '1级',
		2 => '2级',
		3 => '3级',
		4 => '4级',
		5 => '5级',
		6 => '6级',
		7 => '7级',
		8 => '8级',
		9 => '9级',
		10 => '10级',
	];

	var $mp_rank = [
		1 => '1级',
		2 => '2级',
		3 => '3级',
		4 => '4级',
		5 => '5级',
	];


	//生成类单例
	

	//取默认值
	function getDefaultRow() {
		return [
			'id' => '',
			'sort' => '99',
			'type' => 'backend',
			'mer_id' => '0',
			'name' => '',
			'status' => '1',
			'desc' => '',
			'expand' => '',
			'rank' => '1',
		];
	}

	//根据条件查询
	function getByCond($param) {
		$default = [
			'field' => '',
			'keyword' => '',
			'status' => '',
			'module' => 'backend',
			'page' => 1,
			'pageSize' => 10,
			'sort' => 'id',
			'order' => 'DESC',
			'count' => FALSE,
			'getAll' => FALSE,
		];

		$param = extend($default, $param);

		$model = $this->getModel()->keyword($param['keyword'])->module($param['module'])->status($param['status'])
			->where('rank', '<', 10);

		if ($param['count']) {
			return $model->count();
		} else {
			//     $this->getModel() = $this->getModel()->select( $param['field'] );
			$data = $model->getAll($param)
				->orderBy($param['order'], $param['sort'])->get()->toArray();

			return $data;

		}
	}

	/**
	 * 根据角色id获取角色信息
	 *
	 *
	 */
	function getById($id) {
		return $this->getModel()->find($id);
	}

	/**
	 * 根据模块获取角色
	 *
	 * @param $module
	 *
	 * @return mixed
	 */
	function getByModule($module) {

		$data = $this->getModel()
			->where('id', '<>', config('backend.superAdminId'))
			->module($module)
			->orderBy('rank', 'desc')
			->get()->toArray();

		return $data;
	}

	public function findById($id){
		return $this->getModel()->find($id);
	}

	/**
   * 添加数据
   *
   * @param $data
   *
   * @return array
   */
  public function insert( $data ) {
    try {
      if ( empty( $data ) ) {
        throw new \Exception( '数据不能为空' );
      }
      //优化创建模块角色
      $role = Role::where('name',$data['name'])->first();

      if(empty($role)){
        $role = Role::create(['name'=>$data['name'],'guard_name'=>'admin']);
      }else{
      	throw new \Exception( '角色已存在' );
      }

      $data['role_id'] = $role->id;
      
      $model = $this->getModel()->create( $data );
      
      return ajax_arr( '创建成功' , 0 , [ 'id' => $model->id ] );
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
      $role = Role::where('name',$data['name'])->first();
      if(empty($role)){
        $role = Role::create(['name'=>$data['name'],'guard_name'=>'admin']);
      }
      $data['role_id'] = $role->id;
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
      //关联删除
      $role_ids = $this->getModel()->whereIn('id',(array)$ids)->pluck('role_id');
      Role::whereIn('id',$role_ids)->delete();
      $rows = $this->getModel()->destroy( $ids );
      if ( $rows == 0 ) {
        return ajax_arr( "未删除任何数据" , 0 );
      }
      
      return ajax_arr( "成功删除{$rows}行数据" , 0 );
    } catch ( \Exception $e ) {
      return ajax_arr( $e->getMessage() , 500 );
    }
  }

  
  public function getPermission($params){
    $params = [
      'module' => $params['module'],
    ]; 
    $sysFuncService = ServiceManager::make(SysFuncService::class);
    $sysFuncs = $sysFuncService->getPermission($params); 

    return $sysFuncs;
  }

  public function getRoles($roles){
    $sysRoles = $this->getModel()->whereIn('id',$roles)->get();
    
  	$roles = collect();
  	foreach($sysRoles as $sysRole){
  		if(isset($sysRole->role)){
  			$roles->push($sysRole->role);
  		}	
    }

  	return $roles;
  }

  public function permissions($id){
	$model = $this->getModel()->find($id);
	return $model->role->getAllPermissions();
  }

  /**
     * 根据角色获取 授权
     *
     * @param $roleId
     *
     * @return mixed
     */
    public function getPermissionByRole( $id ) {

        //通过原roleid 获取 库roleId
        $sysRole = $this->getById($id);
        if(empty($sysRole)){
          throw new \Exception('当前角色不存在');
        }

        $data = [];
        
        if(isset($sysRole->role->permissions)){

            $permissions = $sysRole->role->permissions->pluck('id');
            $permissionService =  PermissionService::instance();
            $permissions = $permissionService->getByIds($permissions);
            
			      $data = $permissions->pluck('node');
            /* foreach($permissions as $permission){
                if(!empty($permission->node)){
                    $data_tmp = [
                        'permission_id' => $permission->node->id
                    ];
                    array_push($data, $data_tmp);
                }
                
            } */
        }
        $data = $data->filter(function($item){
          if($item){
            return true;
          }else{
            return false;
          }
        });
        return $data->values();

	}
	
	/**
     * 更新角色授权
     *
     * @param $roleId
     * @param $privilegeArr
     *
     * @return array
     */
    public function updateRolePermission( $roleId, $nodeArr ) {

        $result = $this->syncPermissions($roleId,$nodeArr);
        
        if(!$result){
            throw new \Exception('更新权限失败');
        }
      return $result;
      
    }
	
	public function syncPermissions($roleId,$nodes){
        $sysRole = $this->getById($roleId);
        //获取node 集合
        $sysPermissionNodeService = SysPermissionNodeService::instance();
        $permission_nodes = $sysPermissionNodeService->getByIds($nodes);
        if(count($nodes) > 0 && $permission_nodes->count() == 0){
          throw new \Exception('选中了权限结点,但并没有这样权限');
        }
        $permissions = [];
        foreach($permission_nodes as $node){
            array_push($permissions, $node->permission->id);
        }
       
        $sysRole->role->syncPermissions($permissions);

        return true;
	}
	
	/**
     * 根据角色获取 授权
     *
     * @param $roleId
     *
     * @return mixed
     * //待废弃
     */
    function getByRole( $roleId ) {

        //通过原roleid 获取 库roleId
        $sysRole = $this->getById($roleId);

        $data = [];
        if(isset($sysRole->role->permissions)){

            $permissions = $sysRole->role->permissions->pluck('id');
            $permissionService = ServiceManager::make(PermissionService::class);
            $permissions = $permissionService->getByIds($permissions);
            

            foreach($permissions as $permission){
                if(!empty($permission->node)){
                    $data_tmp = [
                        'privilege_id' => $permission->node->id
                    ];
                    array_push($data, $data_tmp);
                }
                
            }
        }
        return $data;
     //   return $this->getModel()->where( 'role_id', $roleId )->get()->toArray();
    }
}