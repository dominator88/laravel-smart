<?php namespace Smart\Models;
/**
 * SysStatistics Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-09-25
 */

use Illuminate\Database\Eloquent\Model;

class SysStatistics extends Model {
    public $table =  'sys_statistics';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;
}
