<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysAppVersion extends Model
{
    public $table = 'sys_app_version';

    public $timestamps = false;

    use \Smart\Traits\Service\Scope;

    public function scopeKeyword($query , $param){
        if($param !== '')
            return $query->where('description' , 'like' , "%{$param}%");
    }
    //
}
