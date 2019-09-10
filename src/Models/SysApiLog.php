<?php namespace Smart\Models;
/**
 * SysApiLog Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-09-25
 */

use Illuminate\Database\Eloquent\Model;

class SysApiLog extends Model {
    public $table =  'sys_api_log';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;

    public $fillable = ['device', 'device_os_version', 'app_version', 'api_version', 'uri', 'ip'];
}
