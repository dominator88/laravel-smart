<?php
namespace Smart\Service;

interface PermissionInterface{

    //是否拥有某个权限
    public function hasAnyPermission($id ,$funcIds, $guard_name = 'admin');

    //获取当前拥有的所有权限
    public function permissions($id);

    //获取当前模型拥有的角色
    public function roles($id);
}