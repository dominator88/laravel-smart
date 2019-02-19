<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/11/14
 * Time: 16:12
 */
//接口路由
Route::group(['prefix'=>'api/{version}','namespace'=>'App\Api' , 'middleware'=> ['api','auth.token','auth.cors']],function(){

    Route::any('{direction}/{action}' , 'Index@index');



});