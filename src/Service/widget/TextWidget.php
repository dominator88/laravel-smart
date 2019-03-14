<?php
namespace Smart\Service\Widget;

class TextWidget {
	private $template = 'backend::widget.text';
	public function render() {
		return view($this->template);
	}

}