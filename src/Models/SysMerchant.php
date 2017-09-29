<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysMerchant extends Model
{
    public $table = 'sys_merchant';

    public $primaryKey = 'id';

    public $timestamps = false;
    
    use \Smart\Traits\Service\Scope;

    public function scopeKeyword($query , $param){
        if($param !== '')
            return $query->where('name' , 'like' , "%{$param}%");
    }

    public function sysUsers(){
        return $this->belongsToMany('Smart\Models\SysUser' , 'mer_sys_user' , 'mer_id' , 'sys_user_id');
    }

}
