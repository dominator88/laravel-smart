<?php namespace Smart\Models;
/**
 * Role Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2019-04-16
 */

use Spatie\Permission\Models\Role as RoleModel;

class Role extends RoleModel {
    public $table =  'roles';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;
}
