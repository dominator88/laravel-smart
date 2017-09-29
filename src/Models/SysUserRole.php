<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysUserRole extends Model
{
    public $table = 'sys_user_role';

    public $primaryKey = 'id';
    
    public $timestamps = false;
    //
    
    use \Smart\Traits\Service\Scope;

    public function scopeName( $query ,$param){
        if($param)
            return $query->where('name' , 'like' , "%{$param}%");
    }
}
