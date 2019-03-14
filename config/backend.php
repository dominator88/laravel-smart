<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 11:07
 */
return [
    'version' => '2.0',

    'baseUri' => '/',
    
    /*
    |--------------------------------------------------------------------------
    | Access via `https`
    |--------------------------------------------------------------------------
    |
    | 后台是否使用https
    |
    */

    'https' => env('BACKEND_HTTPS',false),

    'projectName' => 'laravel-smart',

    'sessionName' => 'backend_session',

    'directory' => ['Api' , 'backend' , 'mp' , 'Service' , 'Models'],

    'superAdminId' => 1,

    'defaultAdmin' => 'sys_admin',

    'defaultPwd' => '123123',

    'defaultEmail' => 'admin@admin.com',

    'areaCachePrefix' => 'backend_area',

    'secret'        => 'laravel-smart-secret',

    'timeGap'   => 300,

    'JPush'      => [
        'appKey' => 'xxx' ,
        'secret' => 'xxx' ,
    ] ,

    'image' => [
        'imgUri' => '',
        'uploadType' => 'local',
    ],

    'sms' => [
        'name' => 'alidayu',
    ],

    'api' => [
        'apiVersion' => 'v1',
    ],

    //自定义扩展模块  默认模块为backend  mp为自定义需要自行在app目录下创建mp文件夹  执行安装时自动生成目录及路由文件
    'module_ext' => env('MODULE_EXT' , 'mp'),
];