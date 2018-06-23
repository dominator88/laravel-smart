<?php namespace Smart\Models;
/**
 * SysUserDevice Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2018-06-18
 */

use Illuminate\Database\Eloquent\Model;

class SysUserDevice extends Model {
	public $table = 'sys_user_device';

	public $primaryKey = 'id';

	public $timestamps = FALSE;

	protected $fillable = ['for_test', 'token', 'device', 'api_version'];

	use \Smart\Traits\Service\Scope;

	public function user() {
		return $this->belongsTo(\Smart\Models\SysUser::class, 'user_id');
	}
}
