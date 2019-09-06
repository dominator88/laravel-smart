<?php namespace Smart\Models;
/**
 * SysFuncExtend Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2019-09-04
 */

use Illuminate\Database\Eloquent\Model;

class SysFuncExtend extends Model {
    public $table =  'sys_func_extends';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;

    public $fillable = ['func_id', 'extend_name','extend_path','extend_component', 'extend_notCache', 'extend_showAlways'];
}
