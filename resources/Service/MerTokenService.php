<?php namespace App\Service;
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/30
 * Time: 15:33
 */
use Illuminate\Support\Facades\DB;
use Smart\Interfaces\TokenService;
use Smart\Service\BaseService;

class MerTokenService extends BaseService implements TokenService{


    public function getByToken($token){
        $data = DB::table('sys_user as su')
            ->join('mer_sys_user as msu' , 'su.id' , '=' , 'msu.sys_user_id')
            ->join('mer_user as mu' , 'mu.id','=' , 'msu.id')
            ->where('su.token' , $token)
            ->first();
        return $data;
    }


}