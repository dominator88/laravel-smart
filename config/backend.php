<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 11:07
 */
return [
    'baseUri' => '/',

    'sessionName' => 'backend_session',

    'directory' => ['Http/Controllers/Api' , 'Http/Controllers/backend' , 'Http/Controllers/mp' , 'Service' , 'Models'],

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
];