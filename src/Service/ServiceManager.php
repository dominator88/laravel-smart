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
	 * @param $classPath
	 *
	 */
	public function register($classPath) {

	}

	/**
	 * 实例化service
	 *
	 */
	public function make($serviceName) {

		return $serviceName::instance();

	}

	/**
	 * 销毁service实例
	 * @param $serviceName
	 */
	public function destroy($serviceName) {

	}

}