<?php
/**
 *	组件服务管理
 *  @author MR.Z
 *	@version v2.0 2018-06-22
 */

namespace Smart\Service;

class WidgetService {
	public $builder = null;

	public $name = null;
	public $value = null;

	private $type = null;

	//设置widget属性值
	public function make($param = []) {

		$this->type = ucfirst($param['type']);
		$WidgetService = '\Smart\Service\Widget\\' . $this->type . 'Widget';
		$this->builder = new $WidgetService;
		$this->builder->make($param);
		return $this;
	}

	public function render() {
		return $this->builder->render();
	}

	public function __toString() {
		return $this->render()->render();
	}
}