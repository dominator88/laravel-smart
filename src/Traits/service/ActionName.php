<?php
namespace Smart\Traits\Service;

trait ActionName{

	/**
 * 获取当前控制器名
 *
 * @return string
 */
	public function getCurrentControllerName()
	{
		return $this->getCurrentAction()['controller'];
	}

	/**
	 * 获取当前方法名
	 *
	 * @return string
	 */
	public function getCurrentMethodName()
	{
		return $this->getCurrentAction()['method'];
	}

	/**
	 * 获取当前控制器与方法
	 *
	 * @return array
	 */
	public function getCurrentAction()
	{
		$action = \Route::current()->getActionName();
		list($class, $method) = explode('@', $action);

		return ['controller' => $class, 'method' => $method];
	}


}