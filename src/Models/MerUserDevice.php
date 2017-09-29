<?php

namespace Smart\Models;

use Illuminate\Database\Eloquent\Model;

class MerUserDevice extends Model
{
    public function User(){
        return $this->belongsTo('Smart\Models\MerUser');
    }
    //
}
