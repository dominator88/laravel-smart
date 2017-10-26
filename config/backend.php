<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 11:07
 */
return [
    'baseUri' => 'http://laravel.local.com/',

    'sessionName' => 'backend_session',

    'directory' => ['Http/Controllers/Api' , 'Http/Controllers/backend' , 'Http/Controllers/mp'],

    'superAdminId' => 1,
    'defaultPwd' => '123123',

    'areaCachePrefix' => 'backend_area',

    'JPush'      => [
        'appKey' => 'xxx' ,
        'secret' => 'xxx' ,
    ] ,

    'imgUri' => '',

    'sms' => [
        'name' => 'alidayu',
    ],
];