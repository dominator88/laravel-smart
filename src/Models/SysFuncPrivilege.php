<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysFuncPrivilege extends Model {
	public $table = 'sys_func_privilege';

	public $primaryKey = 'id';

	public $timestamps = false;
	//

	public function sysFunc() {
		return $this->belongsTo(\Smart\Models\SysFunc::class, 'func_id');
	}
}
