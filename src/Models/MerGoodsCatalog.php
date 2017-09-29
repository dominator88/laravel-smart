<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class MerGoodsCatalog extends Model
{
    public $table = 'mer_goods_catalog';

    public $primaryKey = 'id';

    public $timestamps = false;

    use \Smart\Traits\Service\Scope,\Smart\Traits\Service\ScopeMer;

    public function scopeKeyword($query , $param){
        if($param !== '')
            return $query->where('text' , 'like' , "%{$param}%");
    }
    //
}
