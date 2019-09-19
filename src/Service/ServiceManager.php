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
	 * 实例化service
	 *
	 */
	public function make($serviceName,$params = []) {
		$service = app()->make($serviceName);

		if($service instanceof ParamService){
			$service->params($params);
		}
		
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