<?php


Route::group(['prefix'=>'backend/index','namespace' => 'Smart\Controllers\Backend' ,'middleware'=> ['web']],function(){
    Route::get('index' ,  'Index@index');
});




//系统功能
Route::group(['prefix'=>'backend/sysfunc','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'SysFunc@index');

    Route::get('read' ,   'SysFunc@read');

    Route::post('update_privilege/{funcId}' ,   'SysFunc@update_privilege');

    Route::post('update/{id}' ,   'SysFunc@update');

    Route::post('insert' ,   'SysFunc@insert');

    Route::post('destroy' ,   'SysFunc@destroy');

});

//系统角色
Route::group(['prefix'=>'backend/sysrole','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'SysRole@index');

    Route::get('read' , 'SysRole@read');

    Route::post('get_permission' , 'SysRole@get_permission');

    Route::post('get_privilegedata' , 'SysRole@get_privilegeData');

    Route::post('update_permission' , 'SysRole@update_permission');

    Route::post('update/{id}' ,   'SysRole@update');

    Route::post('insert' ,   'SysRole@insert');

    Route::post('destroy' ,   'SysRole@destroy');

});

//系统用户
Route::group(['prefix'=>'backend/sysuser','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'SysUser@index');

    Route::get('read' , 'SysUser@read');

    Route::post('get_permission' , 'SysUser@get_permission');

    Route::post('get_privilegedata' , 'SysUser@get_privilegeData');

    Route::post('update_permission' , 'SysUser@update_permission');

    Route::post('update/{id}' ,   'SysUser@update');

    Route::post('insert' ,   'SysUser@insert');

    Route::post('destroy' ,   'SysUser@destroy');

    Route::get('reset_pwd/{id}' ,   'SysUser@reset_pwd');

    Route::get('read_album_catalog' ,   'SysUser@read_album_catalog');

    Route::get('read_album' ,   'SysUser@read_album');

});

//区域管理
Route::group(['prefix'=>'backend/sysarea','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'SysArea@index');

    Route::get('read' , 'SysArea@read');

    Route::post('get_permission' , 'SysArea@get_permission');

    Route::post('get_privilegedata' , 'SysArea@get_privilegeData');

    Route::post('update_permission' , 'SysArea@update_permission');

    Route::post('update/{id}' ,   'SysArea@update');

    Route::post('insert' ,   'SysArea@insert');

    Route::post('destroy' ,   'SysArea@destroy');

});


//商品分类
Route::group(['prefix'=>'backend/mergoodscatalog','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'MerGoodsCatalog@index');

    Route::get('read' , 'MerGoodsCatalog@read');

    Route::post('get_permission' , 'MerGoodsCatalog@get_permission');

    Route::post('get_privilegedata' , 'MerGoodsCatalog@get_privilegeData');

    Route::post('update_permission' , 'MerGoodsCatalog@update_permission');

    Route::post('update/{id}' ,   'MerGoodsCatalog@update');

    Route::post('insert' ,   'MerGoodsCatalog@insert');

    Route::post('destroy' ,   'MerGoodsCatalog@destroy');

});

//机构管理
Route::group(['prefix'=>'backend/sysmerchant','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'SysMerchant@index');

    Route::get('read' , 'SysMerchant@read');



    Route::get('read_detail/{id}' , 'SysMerchant@read_detail');

    Route::get('read_area/{pid}' , 'SysMerchant@read_area');

    Route::post('update/{id}' ,   'SysMerchant@update');

    Route::post('insert' ,   'SysMerchant@insert');

    Route::post('destroy' ,   'SysMerchant@destroy');

});

//机构功能
Route::group(['prefix'=>'backend/merfunc','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'MerFunc@index');

    Route::get('read' , 'MerFunc@read');

    Route::post('update/{id}' ,   'MerFunc@update');

    Route::post('insert' ,   'MerFunc@insert');

    Route::post('destroy' ,   'MerFunc@destroy');

    Route::post('update_privilege/{funcId}' ,   'MerFunc@update_privilege');



});

//机构角色
Route::group(['prefix'=>'backend/merrole','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'MerRole@index');

    Route::get('read' , 'MerRole@read');

    Route::post('update/{id}' ,   'MerRole@update');

    Route::post('insert' ,   'MerRole@insert');

    Route::post('destroy' ,   'MerRole@destroy');

    Route::post('get_permission' ,   'MerRole@get_permission');

    Route::post('get_privilegedata' , 'MerRole@get_privilegeData');

    Route::post('update_permission' , 'MerRole@update_permission');




});

//系统用户
Route::group(['prefix'=>'backend/mersysuser','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index/{merId?}' , 'MerSysUser@index');

    Route::get('read' , 'MerSysUser@read');

    Route::post('update/{id}' ,   'MerSysUser@update');

    Route::post('insert/{merId}' ,   'MerSysUser@insert');

    Route::post('destroy/{merId}' ,   'MerSysUser@destroy');

    Route::get('reset_pwd/{id}' ,   'MerSysUser@reset_pwd');

});

Route::group(['prefix'=>'backend/meruser','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'MerUser@index');

    Route::get('read' , 'MerUser@read');

    Route::post('get_permission' , 'MerUser@get_permission');

    Route::post('get_privilegedata' , 'MerUser@get_privilegeData');

    Route::post('update_permission' , 'MerUser@update_permission');

    Route::post('update/{id}' ,   'MerUser@update');

    Route::post('insert' ,   'MerUser@insert');

    Route::post('destroy' ,   'MerUser@destroy');

    Route::get('reset_pwd/{id}' , 'MerUser@reset_pwd');

    Route::get('read_album_catalog' ,   'MerUser@read_album_catalog');

    Route::post('upload' ,   'MerUser@upload');

    Route::get('read_album' ,   'MerUser@read_album');

});

//APP版本管理
Route::group(['prefix'=>'backend/sysappversion','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'SysAppVersion@index');

    Route::get('read' , 'SysAppVersion@read');

    Route::post('update/{id}' ,   'SysAppVersion@update');

    Route::post('insert' ,   'SysAppVersion@insert');

    Route::post('destroy' ,   'SysAppVersion@destroy');

});

//消息推送
Route::group(['prefix'=>'backend/syspush','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'syspush@index');

    Route::get('read' , 'syspush@read');

    Route::post('update/{id}' ,   'syspush@update');

    Route::post('insert' ,   'syspush@insert');

    Route::post('destroy' ,   'syspush@destroy');

});

//短信
Route::group(['prefix'=>'backend/syssms','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'syssms@index');

    Route::get('read' , 'syssms@read');

    Route::post('update/{id}' ,   'syssms@update');

    Route::post('insert' ,   'syssms@insert');

    Route::post('destroy' ,   'syssms@destroy');

});

//邮件
Route::group(['prefix'=>'backend/sysmail','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'sysmail@index');

    Route::get('read' , 'sysmail@read');

    Route::post('update/{id}' ,   'sysmail@update');

    Route::post('insert' ,   'sysmail@insert');

    Route::post('destroy' ,   'sysmail@destroy');

    Route::post('send' ,   'sysmail@send');

});

//代码生成
Route::group(['prefix'=>'backend/generate','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'Generate@index');

    Route::get('get_system_info' , 'Generate@get_system_info');

    Route::post('create_system' ,  'Generate@create_system');

    Route::post('create_api' ,  'Generate@create_api');

    Route::get('destroy_system_file' ,  'Generate@destroy_system_file');



});

//接口模拟器
Route::group(['prefix'=>'backend/simulator','namespace'=>'Smart\Controllers\Backend','middleware'=> ['web']],function(){

    Route::get('index' , 'Simulator@index');

    Route::get('read_api' , 'Simulator@read_api');

    Route::get('read_params' , 'Simulator@read_params');

});


//接口路由
Route::group(['prefix'=>'api/{version}','namespace'=>'App\Http\Controllers\Api' , 'middleware'=> ['api']],function(){

    Route::any('{direction}/{action}' , 'Index@index');



});


