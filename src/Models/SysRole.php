<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class SysRole extends Model
{
    public $table = 'sys_role';

    public $primaryKey = 'id';

    public $timestamps = false ;

    use \Smart\Traits\Service\Scope;
}
