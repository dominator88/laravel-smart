<?php namespace Smart\Models;
/**
 * SysSettings Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2018-06-25
 */

use Illuminate\Database\Eloquent\Model;

class SysSettings extends Model {
	public $table = 'sys_settings';

	public $primaryKey = 'id';

	public $timestamps = True;

	use \Smart\Traits\Service\Scope;
}
