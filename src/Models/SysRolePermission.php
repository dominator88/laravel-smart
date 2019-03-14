<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysRolePermission extends Model {
	public $table = 'sys_role_permission';

	public $primaryKey = 'id';

	public $timestamps = false;

	use \Smart\Traits\Service\Scope;

	public function sysFuncs() {
		return $this->belongsToMany(Smart\Models\SysFunc::class, 'sys_func_privilege', 'func_id', 'id');
	}
}
