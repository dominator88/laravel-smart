<?php namespace Smart\Traits\Service;
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 18:06
 */

trait Scope{

    public function scopeModule($query , $param = '' ){

        if( $param !== '')
            return $query->where('module',$param);

    }


    public function scopeStatus($query , $param = ''){
        if($param !== '')
            return $query->where('status',$param);
    }

    public function scopeKeyword($query , $param = ''){
        if($param)
            return $query->where('keyword' , 'like' , "%{$param}%");
    }



    public function scopeGetAll($query , $params = ['getAll'=>false]){
        if(!$params['getAll'])
            return $query->skip(($params['page']-1) * $params['pageSize'] )->take($params['pageSize'] );


    }
}
