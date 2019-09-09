<?php

use Illuminate\Routing\Router;
Route::group([
	'prefix'=>strtolower('Backend' ),
	'namespace' => 'Smart\\Controllers\\Backend' ,
	'middleware'=> ['web','auth']
],function(Router $router ){
	$router->group(['prefix' => 'auth','middleware' => ['auth.resetPassword']],function($router){
		$router->post('changepassword' , 'AuthController@changePassword');
	});

	//首页
	$router->get('index/index', 'Index@index');

	
//权限节点
	$router->group(['prefix' => 'syspermissionnode'] , function($router){

		$router->get('index' , 'SysPermissionNode@index');

		$router->get('read', 'SysPermissionNode@read');

		$router->post('insert' , 'SysPermissionNode@insert');

		$router->post('update/{id}' ,'SysPermissionNode@update');

		$router->post('destroy','SysPermissionNode@destroy');

		$router->post('getprivilege','SysPermissionNode@getPrivilege');

	});

	//系统功能
	$router->group(['prefix' => 'sysfunc'] , function($router){

		$router->get('index', 'SysFunc@index');

		$router->get('read', 'SysFunc@read');

		$router->post('update_privilege/{funcId}', 'SysFunc@update_privilege');

		$router->post('update/{id}', 'SysFunc@update');

		$router->post('insert', 'SysFunc@insert');

		$router->post('destroy', 'SysFunc@destroy');
	});

	//系统角色
	$router->group(['prefix' => 'sysrole'] , function($router){

		$router->get('index', 'SysRole@index');

		$router->get('read', 'SysRole@read');

		$router->post('getpermission', 'SysRole@getPermission');

		$router->post('get_privilegedata', 'SysRole@get_privilegeData');

		$router->post('update_permission', 'SysRole@update_permission');

		$router->post('update/{id}', 'SysRole@update');

		$router->post('insert', 'SysRole@insert');

		$router->post('destroy', 'SysRole@destroy');
	});

	//系统用户
	$router->group(['prefix' => 'sysuser'] , function($router){

		$router->get('index', 'SysUser@index');

		$router->get('read', 'SysUser@read');

		$router->post('get_permission', 'SysUser@get_permission');

		$router->post('get_privilegedata', 'SysUser@get_privilegeData');

		$router->post('update_permission', 'SysUser@update_permission');

		$router->post('update/{id}', 'SysUser@update');

		$router->post('insert', 'SysUser@insert');

		$router->post('destroy', 'SysUser@destroy');

		$router->get('reset_pwd/{id}', 'SysUser@reset_pwd');

		$router->get('read_album_catalog', 'SysUser@read_album_catalog');

		$router->get('read_album', 'SysUser@read_album');

		$router->post('upload', 'SysUser@upload');

		$router->post('test', 'SysUser@test');
	});

	//区域管理
	$router->group(['prefix' => 'sysarea'] , function($router){
		
		$router->get('index', 'SysArea@index');

		$router->get('read', 'SysArea@read');

		$router->post('get_permission', 'SysArea@get_permission');

		$router->post('get_privilegedata', 'SysArea@get_privilegeData');

		$router->post('update_permission', 'SysArea@update_permission');

		$router->post('update/{id}', 'SysArea@update');

		$router->post('insert', 'SysArea@insert');

		$router->post('destroy', 'SysArea@destroy');
	});

	$router->group(['prefix' => 'sysmerchant'] , function($router){
		
		$router->get('index', 'SysMerchant@index');

		$router->get('read', 'SysMerchant@read');

		$router->get('read_detail/{id}', 'SysMerchant@read_detail');

		$router->get('read_area/{pid}', 'SysMerchant@read_area');

		$router->post('update/{id}', 'SysMerchant@update');

		$router->post('insert', 'SysMerchant@insert');

		$router->post('destroy', 'SysMerchant@destroy');

		$router->post('upload', 'SysMerchant@upload');

		$router->get('read_album_catalog','SysMerchant@read_album_catalog');
	});

	$router->group(['prefix' => 'merfunc'] , function($router){
		
		$router->get('index', 'MerFunc@index');

		$router->get('read', 'MerFunc@read');

		$router->post('update/{id}', 'MerFunc@update');

		$router->post('insert', 'MerFunc@insert');

		$router->post('destroy', 'MerFunc@destroy');

		$router->post('updateprivilege/{funcId}', 'MerFunc@updatePrivilege');
	});
	
	$router->group(['prefix' => 'merrole'] , function($router){

		$router->get('index', 'MerRole@index');

		$router->get('read', 'MerRole@read');

		$router->post('update/{id}', 'MerRole@update');

		$router->post('insert', 'MerRole@insert');

		$router->post('destroy', 'MerRole@destroy');

		$router->post('get_permission', 'MerRole@get_permission');

		$router->post('get_privilegedata', 'MerRole@get_privilegeData');

		$router->post('update_permission', 'MerRole@update_permission');

		$router->post('getpermission', 'MerRole@getPermission');

		$router->post('getprivilegedata', 'MerRole@getPrivilegeData');

		$router->post('updatepermission', 'MerRole@updatePermission');
	});

	$router->group(['prefix' => 'meruser'] , function($router){
		
		$router->get('index', 'MerUser@index');

		$router->get('read', 'MerUser@read');

		$router->post('get_permission', 'MerUser@get_permission');

		$router->post('get_privilegedata', 'MerUser@get_privilegeData');

		$router->post('update_permission', 'MerUser@update_permission');

		$router->post('update/{id}', 'MerUser@update');

		$router->post('insert', 'MerUser@insert');

		$router->post('destroy', 'MerUser@destroy');

		$router->get('reset_pwd/{id}', 'MerUser@reset_pwd');

		$router->get('read_album_catalog', 'MerUser@read_album_catalog');

		$router->post('upload', 'MerUser@upload');

		$router->get('read_album', 'MerUser@read_album');
	});

	$router->group(['prefix' => 'sysappversion'] , function($router){

		$router->get('index', 'SysAppVersion@index');

		$router->get('read', 'SysAppVersion@read');

		$router->post('update/{id}', 'SysAppVersion@update');

		$router->post('insert', 'SysAppVersion@insert');

		$router->post('destroy', 'SysAppVersion@destroy');
	});

	$router->group(['prefix' => 'syspush'] , function($router){

		$router->get('index', 'SysPush@index');

		$router->get('read', 'SysPush@read');

		$router->post('update/{id}', 'SysPush@update');

		$router->post('insert', 'SysPush@insert');

		$router->post('destroy', 'SysPush@destroy');
	});

	$router->group(['prefix' => 'syssms'] , function($router){

		$router->get('index', 'SysSms@index');

		$router->get('read', 'SysSms@read');

		$router->post('update/{id}', 'SysSms@update');

		$router->post('insert', 'SysSms@insert');

		$router->post('destroy', 'SysSms@destroy');
	});

	$router->group(['prefix' => 'sysmail'] , function($router){

		$router->get('index', 'SysMail@index');

		$router->get('read', 'SysMail@read');

		$router->post('update/{id}', 'SysMail@update');

		$router->post('insert', 'SysMail@insert');

		$router->post('destroy', 'SysMail@destroy');

		$router->post('send', 'SysMail@send');
	});

	$router->group(['prefix' => 'generate'] , function($router){

		$router->get('index', 'Generate@index');

		$router->get('get_system_info', 'Generate@get_system_info');

		$router->post('create_system', 'Generate@create_system');

		$router->post('create_api', 'Generate@create_api');

		$router->get('destroy_system_file', 'Generate@destroy_system_file');
	});

	$router->group(['prefix' => 'meralbum'] , function($router){

		$router->get('index/{id?}', 'MerAlbum@index');

		$router->get('read', 'MerAlbum@read');
	});

	$router->group(['prefix' => 'meralbumcatalog'] , function($router){

		$router->get('index', 'MerAlbumCatalog@index');

		$router->get('read', 'MerAlbumCatalog@read');

		$router->get('read_album/{id}', 'MerAlbumCatalog@read_album');
	});

	$router->group(['prefix' => 'simulator'] , function($router){

		$router->get('index', 'Simulator@index');

		$router->get('read_api', 'Simulator@read_api');

		$router->get('read_params', 'Simulator@read_params');
	});
});




//接口路由
Route::group(['prefix' => 'api/{version}', 'namespace' => 'App\Http\Controllers\Api', 'middleware' => ['api','auth.cors']], function () {

	Route::any('{direction}/{action}', 'Index@index');

});




/* //商品分类
Route::group(['prefix' => 'backend/mergoodscatalog', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'MerGoodsCatalog@index');

	Route::get('read', 'MerGoodsCatalog@read');

	Route::post('get_permission', 'MerGoodsCatalog@get_permission');

	Route::post('get_privilegedata', 'MerGoodsCatalog@get_privilegeData');

	Route::post('update_permission', 'MerGoodsCatalog@update_permission');

	Route::post('update/{id}', 'MerGoodsCatalog@update');

	Route::post('insert', 'MerGoodsCatalog@insert');

	Route::post('destroy', 'MerGoodsCatalog@destroy');

}); */

//机构管理
/* Route::group(['prefix' => 'backend/sysmerchant', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'SysMerchant@index');

	Route::get('read', 'SysMerchant@read');

	Route::get('read_detail/{id}', 'SysMerchant@read_detail');

	Route::get('read_area/{pid}', 'SysMerchant@read_area');

	Route::post('update/{id}', 'SysMerchant@update');

	Route::post('insert', 'SysMerchant@insert');

	Route::post('destroy', 'SysMerchant@destroy');

	Route::post('upload', 'SysMerchant@upload');

	Route::get('read_album_catalog','SysMerchant@read_album_catalog');

});
 */
//机构功能
/* Route::group(['prefix' => 'backend/merfunc', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'MerFunc@index');

	Route::get('read', 'MerFunc@read');

	Route::post('update/{id}', 'MerFunc@update');

	Route::post('insert', 'MerFunc@insert');

	Route::post('destroy', 'MerFunc@destroy');

	Route::post('updateprivilege/{funcId}', 'MerFunc@updatePrivilege');

}); */

//机构角色
/* Route::group(['prefix' => 'backend/merrole', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'MerRole@index');

	Route::get('read', 'MerRole@read');

	Route::post('update/{id}', 'MerRole@update');

	Route::post('insert', 'MerRole@insert');

	Route::post('destroy', 'MerRole@destroy');

	Route::post('get_permission', 'MerRole@get_permission');

	Route::post('get_privilegedata', 'MerRole@get_privilegeData');

	Route::post('update_permission', 'MerRole@update_permission');

	Route::post('getpermission', 'MerRole@getPermission');

	Route::post('getprivilegedata', 'MerRole@getPrivilegeData');

	Route::post('updatepermission', 'MerRole@updatePermission');

}); */

//系统用户
/* Route::group(['prefix' => 'backend/mersysuser', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index/{merId?}', 'MerSysUser@index');

	Route::get('read', 'MerSysUser@read');

	Route::post('update/{id}', 'MerSysUser@update');

	Route::post('insert/{merId}', 'MerSysUser@insert');

	Route::post('destroy/{merId}', 'MerSysUser@destroy');

	Route::get('reset_pwd/{id}', 'MerSysUser@reset_pwd');

}); */

/* Route::group(['prefix' => 'backend/meruser', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'MerUser@index');

	Route::get('read', 'MerUser@read');

	Route::post('get_permission', 'MerUser@get_permission');

	Route::post('get_privilegedata', 'MerUser@get_privilegeData');

	Route::post('update_permission', 'MerUser@update_permission');

	Route::post('update/{id}', 'MerUser@update');

	Route::post('insert', 'MerUser@insert');

	Route::post('destroy', 'MerUser@destroy');

	Route::get('reset_pwd/{id}', 'MerUser@reset_pwd');

	Route::get('read_album_catalog', 'MerUser@read_album_catalog');

	Route::post('upload', 'MerUser@upload');

	Route::get('read_album', 'MerUser@read_album');

}); */

//APP版本管理
/* Route::group(['prefix' => 'backend/sysappversion', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'SysAppVersion@index');

	Route::get('read', 'SysAppVersion@read');

	Route::post('update/{id}', 'SysAppVersion@update');

	Route::post('insert', 'SysAppVersion@insert');

	Route::post('destroy', 'SysAppVersion@destroy');

}); */

//消息推送
/* Route::group(['prefix' => 'backend/syspush', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'SysPush@index');

	Route::get('read', 'SysPush@read');

	Route::post('update/{id}', 'SysPush@update');

	Route::post('insert', 'SysPush@insert');

	Route::post('destroy', 'SysPush@destroy');

}); */

//短信
/* Route::group(['prefix' => 'backend/syssms', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'SysSms@index');

	Route::get('read', 'SysSms@read');

	Route::post('update/{id}', 'SysSms@update');

	Route::post('insert', 'SysSms@insert');

	Route::post('destroy', 'SysSms@destroy');

}); */

//邮件
/* Route::group(['prefix' => 'backend/sysmail', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'SysMail@index');

	Route::get('read', 'SysMail@read');

	Route::post('update/{id}', 'SysMail@update');

	Route::post('insert', 'SysMail@insert');

	Route::post('destroy', 'SysMail@destroy');

	Route::post('send', 'SysMail@send');

}); */

//代码生成
/* Route::group(['prefix' => 'backend/generate', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'Generate@index');

	Route::get('get_system_info', 'Generate@get_system_info');

	Route::post('create_system', 'Generate@create_system');

	Route::post('create_api', 'Generate@create_api');

	Route::get('destroy_system_file', 'Generate@destroy_system_file');

}); */

/* Route::group(['prefix' => 'backend/meralbum', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index/{id?}', 'MerAlbum@index');

	Route::get('read', 'MerAlbum@read');

}); */

/* Route::group(['prefix' => 'backend/meralbumcatalog', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'MerAlbumCatalog@index');

	Route::get('read', 'MerAlbumCatalog@read');

	Route::get('read_album/{id}', 'MerAlbumCatalog@read_album');

}); */

/* Route::group([
	'prefix' => strtolower('Backend'),
	'namespace' => 'Smart\\Controllers\\Backend',
	'middleware' => ['web', 'auth.permission'],
], function () {


	Route::group(['prefix' => 'modulefunc'], function () {

		Route::get('index', 'ModuleFunc@index')->name('backend.modulefunc.index');

		Route::get('read', 'ModuleFunc@read')->name('backend.modulefunc.read');

		Route::post('insert', 'ModuleFunc@insert')->name('backend.modulefunc.insert');

		Route::post('update/{id}', 'ModuleFunc@update')->name('backend.modulefunc.update');

		Route::post('destroy', 'ModuleFunc@destroy')->name('backend.modulefunc.destroy');
	});

	Route::group(['prefix' => 'modulerole'], function () {
		Route::get('index', 'ModuleRole@index')->name('backend.modulerole.index')->middleware('auth.permission');

		Route::get('read', 'ModuleRole@read')->name('backend.modulerole.read');

		Route::post('insert', 'ModuleRole@insert')->name('backend.modulerole.insert');

		Route::post('update/{id}', 'ModuleRole@update')->name('backend.modulerole.update');

		Route::post('destroy', 'ModuleRole@destroy')->name('backend.modulerole.destroy');
	});
}); */

//接口模拟器
/* Route::group(['prefix' => 'backend/simulator', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'Simulator@index');

	Route::get('read_api', 'Simulator@read_api');

	Route::get('read_params', 'Simulator@read_params');

}); */

//设置配置
/* Route::group(['prefix' => 'backend/syssettings', 'namespace' => 'Smart\Controllers\Backend', 'middleware' => ['web']], function () {

	Route::get('index', 'SysSettings@index');

	Route::get('read', 'SysSettings@read');

	Route::post('insert', 'SysSettings@insert');

	Route::post('update/{id}', 'SysSettings@update');

	Route::post('destroy', 'SysSettings@destroy');

	Route::get('group/{group}', 'SysSettings@indexGroup');

}); */





/*******************************下面是api******************************************************************* */

Route::group([
	'prefix'=>strtolower('Backend/api' ),
	'namespace' => 'Smart\\Controllers\\Backend' ,
	'middleware'=> ['api' ,'authentication.access_token:api']
],function(Router $router ){
	$router->group(['prefix' => 'auth','middleware' => ['auth.resetPassword']],function($router){
		$router->post('changepassword' , 'AuthController@changePassword');
	});

	
//权限节点
	$router->group(['prefix' => 'syspermissionnode'] , function($router){

		$router->get('index' , 'SysPermissionNode@index');

		$router->get('read', 'SysPermissionNode@read');

		$router->post('insert' , 'SysPermissionNode@insert');

		$router->post('update/{id}' ,'SysPermissionNode@update');

		$router->post('destroy','SysPermissionNode@destroy');

		$router->post('getprivilege','SysPermissionNode@getPrivilege');

	});

	$router->group(['prefix' => 'sysfunc'] , function($router){

		$router->get('index', 'SysFunc@index');

		$router->get('read', 'SysFunc@read');

		$router->post('update_privilege/{funcId}', 'SysFunc@update_privilege');

		$router->post('update/{id}', 'SysFunc@update');

		$router->post('insert', 'SysFunc@insert');

		$router->post('destroy', 'SysFunc@destroy');

	});
	$router->group(['prefix' => 'sysfunc'] , function($router){

		$router->get('index', 'SysRole@index');

		$router->get('read', 'SysRole@read');

		$router->post('getpermission', 'SysRole@getPermission');

		$router->post('get_privilegedata', 'SysRole@get_privilegeData');

		$router->post('update_permission', 'SysRole@update_permission');

		$router->post('update/{id}', 'SysRole@update');

		$router->post('insert', 'SysRole@insert');

		$router->post('destroy', 'SysRole@destroy');

	});
	
});

