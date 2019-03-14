<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class MerAlbumCatalog extends Model
{
    public $table = 'mer_album_catalog';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    public $fillable = ['tag','mer_id','sort','icon','totals'];

    use \Smart\Traits\Service\Scope;

    public function scopeKeyword( $query , $param){
        if($param)
            return $query->where('name' , 'like' , "%{$param}%");
    }

    public function scopeMerId( $query , $param){
        if($param === ''){
            return $query->whereNull('mer_id');
        }else{
            return $query->where('mer_id' , $param);
        }
    }

    public function album(){
        return $this->belongsToMany( MerAlbum::class , 'mer_album_tag' , 'catalog_id' , 'album_id' );
    }
    //
}
