<?php
namespace Smart\Traits\Service;

use Smart\Service\SysPermissionNodeService;
use Smart\Service\SysRoleService;

trait Permission{

    //用户是否拥有某个功能的权限
	/**
	 * @param $id 用户id
	 * @param $funcIds 权限节点集合
	 */
	public function hasAnyPermission($id ,$nodeIds, $guard_name = 'admin'){
		$user = $this->getModel()->find($id);
		//获取所有功能id
		$sysPermissionNodeService = SysPermissionNodeService::instance();
		$permissions = $sysPermissionNodeService->getPermissions($nodeIds);
		
		return $user->hasAnyPermission($permissions->pluck('id')->toArray(), $guard_name);
		
	}

	//获取用户拥有的权限
	public function permissions($id){
		$sysUser = $this->getModel()->find($id);
		$permissions = $sysUser->getAllPermissions();
		return $permissions;
	}

	//用户拥有的角色 列出
	public function roles($id,$module = []){
		$model = $this->getModel()->find($id);
		$roles = $model->roles;
	//	dd($roles);
		$sysRoles = collect();
		foreach($roles as $role){
            if($role->sysRole){
                $sysRole = $role->sysRole;
                if((count($module) > 0 && in_array($sysRole->module,$module)) || count($module) == 0 ){
                    $sysRoles->push( $role->sysRole);
                }
            }
		}
		return $sysRoles;
	}	

	//更新用户角色
	private function updateRoles($id,$roleIds){
        $model = $this->getModel()->find($id);
        $sysRoleService = SysRoleService::instance();
        $roles = $sysRoleService->getRoles($roleIds);
      
        $model->syncRoles($roles);
        return true;
    }
}