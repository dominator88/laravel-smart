<?php namespace App\Models;
/**
 * MerAlbumTag Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-10-02
 */

use Illuminate\Database\Eloquent\Model;

class MerAlbumTag extends Model {
    public $table =  'mer_album_tag';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;
}
