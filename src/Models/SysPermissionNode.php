<?php namespace Smart\Models;
/**
 * SysPermissionNode Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2019-05-28
 */

use Illuminate\Database\Eloquent\Model;
use Smart\Models\Permission;

class SysPermissionNode extends Model {
    public $table =  'sys_permission_node';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    public $fillable = ['name', 'pid', 'level', 'type', 'symbol', 'status', 'sort','permission_id','module','source_id'];

    use \Smart\Traits\Service\Scope;

    public function children(){
    	return $this->hasMany(SysPermissionNode::class, 'pid');
    }

    public function scopeSymbol($query , $param = ''){
    	if($param !== '')
            return $query->where('symbol','like',"{$param}%");
    }

    public function scopeType($query , $param = ''){
    	if($param !== '')
            return $query->where('type',$param);
    }

    public function permission(){
    	return $this->belongsTo(Permission::class ,'permission_id');
    }

    public function privilege(){
        return $this->hasOne(\Smart\Models\SysFuncPrivilege::class, 'node_id');
    }

    public function scopeModule($query,$param){
        if($param != ''){
            return $query->where('module',$param);
        }
    }

    public function scopeSourceId($query, $param){
        if($param != ''){
            return $query->where('source_id',$param);
        }
    }

}
