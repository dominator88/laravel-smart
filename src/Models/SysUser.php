<?php

namespace Smart\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SysUser extends Authenticatable {
	use Notifiable;
	protected $table = 'sys_user';

	public $primaryKey = 'id';

	public $timestamps = FALSE;

	protected $fillable = ['id','module','username','password','icon','email','phone','status','api_token','signed_at','signed_ip','remember_token','name'];

	use \Smart\Traits\Service\Scope;

	public function sysMerchants() {
		return $this->belongsToMany('Smart\Models\SysMerchant', 'mer_sys_user', 'sys_user_id', 'mer_id');
	}

	public function sysRole() {
		return $this->belongsToMany(SysRole::class, 'sys_user_role', 'user_id', 'role_id');
	}

	public function UserDevice() {
		return $this->hasOne(\Smart\Models\SysUserDevice::class, 'user_id');
	}

	public function scopeKeyword($query, $param) {
		if ($param) {
			return $query->where(function ($query) use ($param) {
				$query->orWhere('username', 'like', "%{$param}%")->orWhere('phone', 'like', "%{$param}%");

			});
		}

	}

	public function username() {
		return 'username';
	}
}
