<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysRole extends Model
{
    public $table = 'sys_role';

    public $primaryKey = 'id';

    public $timestamps = false ;

    use \Smart\Traits\Service\Scope;

    public function rolePermission(){
        return $this->belongsToMany(SysFunc::class , 'sys_role_permission' , 'role_id' , 'privilege_id');
    }
}
