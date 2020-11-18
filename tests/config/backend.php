<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 11:07
 */
return [
    'baseUri' => '/',

    'projectName' => 'laravel-smart',

    'sessionName' => 'backend_session',

    'directory' => ['Http/Controllers/Api' , 'Http/Controllers/backend' ,  'Service' , 'Models'],

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

    //TESTS
    'route' => [
        'prefix' => 'admin',
    ],

    'auth' => [
        'guards' => [

            'admin' => [
                'driver' => 'session',
                'provider' => 'admin',
            ],
        ],
        'providers' => [

            'admin' => [
                'driver' => 'eloquent',
                'model' => Smart\Models\SysUser::class,
            ],
        ]
    ]
];