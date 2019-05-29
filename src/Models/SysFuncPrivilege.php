<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysFuncPrivilege extends Model {
	public $table = 'sys_func_privilege';

	public $primaryKey = 'id';

	public $timestamps = false;
	//
	public $fillable = ['name','node_id','func_id'];

	use \Smart\Traits\Service\Scope;
	
	public function sysFunc() {
		return $this->belongsTo(\Smart\Models\SysFunc::class, 'func_id');
	}

	public function node(){
		return $this->belongsTo(\Smart\Models\SysPermissionNode::class, 'node_id');
	}
}
