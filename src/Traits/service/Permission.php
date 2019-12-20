<?php
namespace Smart\Traits\Service;

use Smart\Models\SysPermissionNode;
use Smart\Models\SysUser;
use Smart\Service\SysPermissionNodeService;
use Smart\Service\SysRoleService;
use Smart\Service\SysUserService;

trait Permission{

	private function getUser($user_id){
		$sysUserService = SysUserService::instance();
		$user = $sysUserService->getById($user_id);
		return $user;
	}

    //用户是否拥有某个功能的权限
	/**
	 * @param $id 用户id
	 * @param $funcIds 权限节点集合
	 */
	public function hasAnyPermission($user_id ,$nodeIds, $guard_name = 'admin'){
		$user = $this->getUser($user_id);
		//获取所有功能id
		$sysPermissionNodeService = SysPermissionNodeService::instance();
		$permissions = $sysPermissionNodeService->getPermissions($nodeIds);
		
		return $user->hasAnyPermission($permissions->pluck('id')->toArray(), $guard_name);
		
	}

	

	//获取用户拥有的权限
	public function permissions($user_id){
		$user = $this->getUser($user_id);
		$permissions = $user->getAllPermissions();
		return $permissions;
	}

	//用户拥有的角色 列出
	public function roles($user_id,$module = [], $type = true){
		//type 为true 查询传递模块,为false 查询传递模板反选
		$user = $this->getUser($user_id);
		$roles = $user->roles;
	//	dd($roles);
		$sysRoles = collect();
		foreach($roles as $role){
            if($role->sysRole){
				$sysRole = $role->sysRole;
				$in_module = in_array($sysRole->module,$module);
                if((count($module) > 0 &&  ($type === true ? $in_module : !$in_module ) ) || count($module) == 0 ){
                    $sysRoles->push( $role->sysRole);
                }
            }
		}
		return $sysRoles;
	}	

	//更新用户角色
	public function updateRoles($user_id,$roleIds){
        try{

			$user = $this->getUser($user_id);
            $sysRoleService = SysRoleService::instance();
            $roles = $sysRoleService->getRoles($roleIds);
      
            $user->syncRoles($roles);
            return true;
          }catch(\Exception $e){
            throw $e;
          }  
	}
	
	public function hasPermission(SysUser $user, SysPermissionNode $permission){
		return $user->hasPermissionTo($permission->permission);
	  }



    
}