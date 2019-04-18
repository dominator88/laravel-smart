<?php

namespace Smart\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class SysUser extends Authenticatable {
	use Notifiable;
	use HasRoles;
	protected $table = 'sys_user';

	public $primaryKey = 'id';

	public $timestamps = FALSE;

	use \Smart\Traits\Service\Scope;

	public function sysMerchants() {
		return $this->belongsToMany('App\Models\SysMerchant', 'mer_sys_user', 'sys_user_id', 'mer_id');
	}

	public function scopeKeyword($query, $param) {
		if ($param) {
			return $query->where(function ($query) use ($param) {
				$query->orWhere('username', 'like', "%{$param}%")->orWhere('phone', 'like', "%{$param}%");

			});
		}

	}

	public function username() {
		return 'name';
	}
}
