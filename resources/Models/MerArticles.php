<?php namespace App\Models;
/**
 * MerArticles Model
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 , 2017-09-30
 */

use Illuminate\Database\Eloquent\Model;

class MerArticles extends Model {
    public $table =  'mer_articles';

    public $primaryKey = 'id';

    public $timestamps = FALSE;

    use \Smart\Traits\Service\Scope;
}
