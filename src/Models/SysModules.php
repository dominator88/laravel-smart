<?php

namespace Smart\Models;


use Illuminate\Database\Eloquent\Model;

class SysModules extends Model
{
    public $table = 'sys_modules';

    public $timestamps = true;

    protected  $fillable = ['name','symbol','displayorder','version','author','status','thumb','desc'];

    use \Smart\Traits\Service\Scope;

   
}
