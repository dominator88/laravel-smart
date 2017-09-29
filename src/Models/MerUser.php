<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class MerUser extends Model
{
    public $table = 'mer_user';

    public $primaryKey = 'id';

    public $timestamps = false;

    /*public function UserDevice(){
        return $this->hasOne('Smart\Models\MerUserDevice');
    }*/

    public function scopeKeyword($query , $param){
        if($param)
            return $query->where(function($query) use ($param){
                $query->orWhere('username' , 'like' , "%{$param}%")->orWhere('nickname' , 'like' , "%{$param}%")->orWhere( 'phone' , 'like' , "%{$param}%");

                });
    }

    public function scopeMerId($query , $param){
        if($param)
            return $query->where('mer_id' , $param);
    }

    public function scopePhone($query , $param){
        if($param)
            return $query->where('phone' , $param);
    }

    public function scopeUsername($query , $param){
        if($param)
            return $query->where( 'username' , $param);
    }

    public function scopeNickname($query , $param){
        if($param)
            return $query->where( 'nickname' , $param);
    }

    public function scopeEmail($query , $param){
        if($param)
            return $query->where('email' , $param );
    }

    public function scopeStatus($query , $param){
        if($param !== '')
            return $query->where('status' , $param);
    }

    public function scopeExcludeId($query , $param){
        if($param)
            return $query->where('id' , '<>' , $param);
    }

    public function scopeGetAll($query , $params = ['getAll'=>false]){
        if(!$params['getAll'])
            return $query->skip(($params['page']-1) * $params['pageSize'] )->take($params['pageSize'] );


    }

}
