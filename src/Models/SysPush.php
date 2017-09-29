<?php

namespace Smart\Models;


use Illuminate\Database\Eloquent\Model;

class SysPush extends Model
{
    public $table = 'sys_push';

    public $timestamps = false;

    use \Smart\Traits\Service\Scope;

    public function scopeKeyword( $query ,$param){
        if($param !== '')
            return $query->where('name' , 'like' , "%{$param}%");
    }
    //
}
