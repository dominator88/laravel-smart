<?php
namespace Smart\Traits\Service;

use Smart\Service\SysPermissionNodeService;
use Smart\Service\SysRoleService;
use Smart\Service\SysUserService;

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
	public function updateRoles($id,$roleIds){
        try{
            $sysUserService = SysUserService::instance();
            $user = $sysUserService->getById($id);
            $sysRoleService = SysRoleService::instance();
            $roles = $sysRoleService->getRoles($roleIds);
            $user->syncRoles($roles);
            return true;
          }catch(\Exception $e){
            throw $e;
          }  
	}
	
	//当前登陆用户是否拥有某个权限
    public function validatePermission($user_id, $permission){
        $sysPermissionNodeService = SysPermissionNodeService::instance();
        $permissionNode = $sysPermissionNodeService->getPermission($permission);
        if(!$permissionNode){
            throw new \Exception('权限节点不存在');
        }
        $sysUserService = SysUserService::instance();
        $user = $sysUserService->getById($user_id);
        $result = $sysUserService->hasPermission($user, $permissionNode);
        if( $result){
            return $result;
        }else{
            throw new \Exception('当前用户并没有该功能的操作权限');
        }
    }

    //通过权限id 或权限标识
    public function getPermission($permission){
        if(is_numeric($permission)){
          return $this->getModel()->find($permission);
        }else{
          return $this->getModel()->where('symbol',$permission)->first();
        }
      }

    
}