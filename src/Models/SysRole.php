<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;
use Smart\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class SysRole extends Model {
	public $table = 'sys_role';

	public $primaryKey = 'id';

	public $timestamps = false;

	public $fillable = ['name','module','status','desc','rank','role_id','sort'];


	use \Smart\Traits\Service\Scope;

	public function sysFuncPrivileges() {
		return $this->belongsToMany(SysFuncPrivilege::class, 'sys_role_permission', 'role_id', 'privilege_id');
	}

	public function role(){
		return $this->belongsTo(Role::class,'role_id');
	}

	public function scopeModule($query,$param){
    	if($param != ''){
    		$query->where('module',$param);
    	}
    }

}
