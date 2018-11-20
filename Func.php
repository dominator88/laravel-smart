<?php
if (!function_exists('full_uri')) {
	function full_uri($uri, $param = []) {
		return url(strtolower($uri), $param);
	}
}

if (!function_exists('extend')) {
	/**
	 * 扩展数组
	 *
	 * @param $config
	 * @param $default
	 *
	 * @return mixed
	 */
	function extend($default, $config) {
		foreach ($default as $key => $val) {
			if (!isset($config[$key]) || $config[$key] === '') {
				$config[$key] = $val;
			} else if (is_array($config[$key])) {
				$config[$key] = extend($val, $config[$key]);
			}
		}

		return $config;
	}
}

if (!function_exists('form_radios')) {
	/**
	 * 水平radio   改良
	 *
	 * @param $name
	 * @param $data
	 * @param int $checked_value
	 *
	 * @return mixed|string
	 */
	function form_radios($name, $data, $checked_value = 0) {
		$html = '';
		foreach ($data as $key => $val) {
			$html .= '<label class="radio-inline"><input name="' . $name . '" type="radio" value="' . $key . '" >' . $val . '</label>';
		}

		if ($checked_value >= 0) {
			$html = str_replace('value="' . $checked_value . '"', "value='$checked_value' checked", $html);
		}

		return $html;
	}
}
if (!function_exists('form_radio')) {
	function form_radio($name, $data, $checked_value = 0, $title) {
		$data = [
			'type' => 'radio',
			'title' => $title,
			'name' => $name,
			'data' => $data,
			'value' => $checked_value,
		];

		return form_field($data);
	}
}

if (!function_exists('form_text2')) {
	function form_text2($name, $value = 0, $title, $help = '', $placeholder = '') {
		$data = [
			'type' => 'text',
			'title' => $title,
			'name' => $name,
			'data' => $data,
			'value' => $value,
			'help' => $help,
			'placeholder' => $placeholder,
		];

		return form_field($data);
	}
}

//use Facades\Smart\Service\WidgetService;
function form_field($param) {

	$widgetService = Facades\Smart\Service\WidgetService::make($param);
	return $widgetService;
}

if (!function_exists("ajax_arr")) {
	/**
	 * 生成需要返回 ajax 数组
	 *
	 * @param $msg        //消息
	 * @param int $code   //0 正常 , > 0 错误
	 * @param array $data //需要传递的参数
	 *
	 * @return array
	 */
	function ajax_arr($msg, $code = 500, $data = []) {
		$arr = [
			'msg' => $msg,
			'code' => $code,
		];

		if ($data !== '') {
			$arr['data'] = $data;
		}

		return $arr;
	}
}

if (!function_exists('form_options')) {
	/**
	 * 生成下拉选项
	 *
	 * @param $data
	 * @param int $selected_value
	 *
	 * @return mixed|string
	 */
	function form_options($data, $selected_value = -1) {
		$html = '';
		foreach ($data as $key => $val) {
			$html .= "<option value='$key'>$val</option>";
		}

		if ($selected_value >= 0) {
			$html = str_replace("value='$selected_value'", "value='$selected_value' selected", $html);
		}

		return $html;
	}

}

if (!function_exists('form_checkbox_rows')) {
	/**
	 * checkbox
	 *
	 * @param $name
	 * @param $data
	 * @param string $key
	 * @param string $val
	 * @param int $checked_value
	 *
	 * @return mixed|string
	 */
	function form_checkbox_rows($name, $data, $key = 'id', $val = 'name', $checked_value = 0) {
		$html = '';
		foreach ($data as $item) {
			$html .= '<label class="checkbox-inline"><input name="' . $name . '[]" type="checkbox" value="' . $item[$key] . '" >' .
				$item[$val] . '</label>';
		}

		if ($checked_value >= 0) {
			$html = str_replace('value="' . $checked_value . '"', "value='$checked_value' checked", $html);
		}

		return $html;
	}
}

if (!function_exists('str2pwd')) {
	/**
	 * 字符串加密
	 *
	 * @param $str
	 *
	 * @return bool|string
	 */
	function str2pwd($str) {
		return password_hash($str, PASSWORD_BCRYPT, ["cost" => 10]);
	}
}

if (!function_exists('json')) {

	function json(Array $array) {
		return response()->json($array);
	}

}
if (!function_exists('api_result')) {

	function api_result($msg, $code_or_data = 500, $data = []) {
		$result = [
			'msg' => $msg,
		];

		if (is_array($code_or_data)) {
			$result['code'] = 0;
			$data = array_merge($code_or_data, $data);
		} else {
			$result['code'] = $code_or_data;
		}

		if (!empty($data)) {
			$result['data'] = $data;
		}

		return $result;
	}
}

if (!function_exists('rand_string')) {
	/**
	 * 生成随机字符串
	 *
	 * @param $length
	 *
	 * @return string
	 */
	function rand_string($length = 6) {
		$str = NULL;
		$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
		$max = strlen($strPol) - 1;

		for ($i = 0; $i < $length; $i++) {
			$str .= $strPol[rand(0, $max)]; // rand($min,$max)生成介于min和max两个数之间的一个随机整数
		}

		return $str;
	}
}

if (!function_exists('form_options_rows')) {
	/**
	 * 生成下拉选项 from rows
	 *
	 * @param $data
	 * @param string $id
	 * @param string $text
	 * @param string $node_field
	 * @param int $selected_value
	 * @param array $dat
	 *
	 * @return mixed|string
	 */
	function form_options_rows($data, $id = 'id', $text = "name", $node_field = "children", $selected_value = 0, $dat = []) {
		$html = '';
		foreach ($data as $row) {
			$value = $row->$id;
			$prefix = '';
			if (isset($row->level)) {
				$prefix = $row->level - 1 > 0 ? str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $row->level - 1) . '└─ ' : ''; // ┗
			}
			$title = $prefix . $row->$text;
			$d = '';
			foreach ($dat as $p) {
				$d .= sprintf(' data-%s="%s"', $p, $row[$p]);
			}
			$html .= sprintf('<option value="%s" %s>%s</option>', $value, $d, $title);

			if (isset($row->$node_field)) {
				$html .= form_options_rows($row[$node_field], $id, $text, 0, $row->level + 1);
			}
		}

		if (!empty($selected_value)) {
			$html = str_replace('value="' . $selected_value . '"', 'value="' . $selected_value . '" selected', $html);
		}

		return $html;
	}

}

if (!function_exists('form_options_rows_group')) {
	/**
	 * optgroup 显示 options
	 *
	 * @param $data
	 * @param $valueField
	 * @param $textField
	 * @param $groupField
	 *
	 * @return string
	 */
	function form_options_rows_group($data, $valueField = 'id', $textField = 'text', $groupField = 'type_text') {
		$newData = [];
		foreach ($data as $item) {
			if (array_key_exists($groupField, $item)) {
				$newData[$item[$groupField]][] = $item;
			}
		}

		$html = '';
		foreach ($newData as $key => $row) {
			$html .= '<optgroup label="' . $key . '">';
			foreach ($row as $r) {
				$html .= '<option value="' . $r[$valueField] . '">' . $r[$textField] . '</option> ';
			}
			$html .= '</optgroup> ';
		}

		return $html;
	}
}

if (!function_exists('file_size')) {
	function file_size($size) {
		$unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
		$index = 0;
		do {
			$size = $size / 1024;
			$index++;
		} while ($size >= 1024);

		return round($size, 1) . $unit[$index];
	}
}

if (!function_exists('full_img_uri')) {
	/**
	 * 返回图片绝对路径
	 *
	 * @param $imgUri
	 *
	 * @return string
	 */
	function full_img_uri($imgUri) {
		if (config('backend.image.uploadType') == 'local') {
			return route($imgUri, '', '', TRUE);
		}

		return config('backend.image.imgUri') . $imgUri;
	}
}

if (!function_exists('css')) {
	/**
	 * 返回图片绝对路径
	 *
	 * @param $imgUri
	 *
	 * @return string
	 */
	function css() {
		if(class_exists(Mp\Service\Common\Asset::class)){
			return  Mp\Service\Common\Asset::css();
		}
		
	}
}

if (!function_exists('js')) {
	/**
	 * 返回图片绝对路径
	 *
	 * @param $imgUri
	 *
	 * @return string
	 */
	function js() {
		if(class_exists(Mp\Service\Common\Asset::class)){
			return  Mp\Service\Common\Asset::js();
		}
	}
}

if (!function_exists('script')) {
	/**
	 * 返回图片绝对路径
	 *
	 * @param $imgUri
	 *
	 * @return string
	 */
	function script() {
		if(class_exists(Mp\Service\Common\Asset::class)){
			return  Mp\Service\Common\Asset::script();
		}
	}
}