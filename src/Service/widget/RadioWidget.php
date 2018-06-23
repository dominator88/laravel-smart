<?php
namespace Smart\Service\Widget;

class RadioWidget {
	private $template = 'backend::widget.radio';
	private $defaultValue = '';
	private $title = '';
	private $data = '';
	public function render() {
		$data = [
			'value' => $this->defaultValue,
			'title' => $this->title,
			'data' => $this->data,
			'name' => $this->name,
		];
		return view($this->template, $data);
	}

	public function make($param) {
		$this->template = $param['template'] ?? $this->template;
		$this->defaultValue = $param['value'];
		$this->title = $param['title'];
		$this->data = $param['data'];
		$this->name = $param['name'];
		return $this;
	}

}