<?php
namespace Smart\Models;
/**
 * SysModules Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2018-06-13
 */

use Illuminate\Database\Eloquent\Model;

class SysModules extends Model {
	public $table = 'sys_modules';

	public $primaryKey = 'id';

	public $timestamps = FALSE;

	use \Smart\Traits\Service\Scope;
}
