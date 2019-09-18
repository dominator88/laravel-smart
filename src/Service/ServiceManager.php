<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/11/4
 * Time: 14:51
 */
namespace Smart\Service;


class ServiceManager {

	public function __construct() {
		
	}

	/**
	 * 注册Service
	 * @param $classPath 为目录id
	 *
	 */
	public function registerModule($module) {
		$path = app_path().'/'.ucfirst($module).'/Service';
		$filesystem = resolve('files');
		$files = $filesystem->allFiles($path);
		$class_prefix = 'App\\'.ucfirst($module).'\\Service\\';
		$file_collect = collect();
		foreach($files as $file){
			$file_collect->push($class_prefix.$filesystem->name($file));
		}
		$file_collect->each(function($item){
			$this->bind($item);
		});
		
	}

	/**
	 * 实例化service
	 *
	 */
	public function make($serviceName,$params = []) {
		$service = app()->make($serviceName);
		$service->params($params);
		return $service;
	}

	//绑定class至容器
	public function bind($className){
		app()->singleton($className,function($app) use ($className){
			return new $className;
		});

	}

	/**
	 * 销毁service实例
	 * @param $serviceName
	 */
	public function destroy($serviceName) {

	}

}