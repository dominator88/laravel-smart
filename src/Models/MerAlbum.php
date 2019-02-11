<?php namespace Smart\Models;
/**
 * MerAlbum Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-09-26
 */

use Illuminate\Database\Eloquent\Model;

class MerAlbum extends Model {
    public $table =  'mer_album';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;

    protected $fillable = ['mer_id','sort','uri','size','mimes','img_size','desc','status'];

    public function scopeKeyword( $query , $param){
        if($param)
            return $query->where('name' , 'like' , "%{$param}%");
    }

    public function tag(){
        return $this->belongsToMany( MerAlbumCatalog::class , 'mer_album_tag' , 'album_id' , 'catalog_id' );
    }
}
