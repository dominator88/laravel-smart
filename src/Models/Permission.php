<?php namespace Smart\Models;
/**
 * Permission Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2019-04-16
 */

use Spatie\Permission\Models\Permission as PermissionModel;
use Smart\Models\SysPermissionNode;

class Permission extends PermissionModel {
    public $table =  'permissions';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    protected $fillable = ['name', 'method','guard_name'];

    use \Smart\Traits\Service\Scope;

    public function scopeGuardName($query , $param = ''){
    	if($param !== '')
            return $query->where('guard_name',$param);
    }

    public function node(){
    	return $this->hasOne(SysPermissionNode::class,'permission_id');
    }
}
