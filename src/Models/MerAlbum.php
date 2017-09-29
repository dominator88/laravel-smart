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

    public function scopeKeyword( $query , $param){
        if($param)
            return $query->where('name' , 'like' , "%{$param}%");
    }
}
