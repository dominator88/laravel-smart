<?php namespace Smart\Service;
/**
 * SysSettings Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2018-06-25
 */

use Smart\Models\SysSettings;
use Smart\Service\BaseService;

class SysSettingsService extends BaseService {

	//引入 GridTable trait
	use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

	protected $model_class = SysSettings::class;

	//状态
	public $status = [
		0 => '禁用',
		1 => '启用',
	];

	

	//取默认值
	function getDefaultRow() {
		return [
			'created_at' => '',
			'desc' => '99',
			'group' => 'default',
			'id' => '',
			'key' => '',
			'type' => 'text',
			'updated_at' => '',
			'value' => '',
		];
	}

	/**
	 * 根据条件查询
	 *
	 * @param $param
	 *
	 * @return array|number
	 */
	public function getByCond($param) {
		$default = [
			'field' => ['*'],
			'keyword' => '',
			'status' => '',
			'page' => 1,
			'pageSize' => 10,
			'sort' => 'id',
			'order' => 'DESC',
			'count' => FALSE,
			'getAll' => FALSE,
		];

		$param = extend($default, $param);

		$model = $this->getModel()->keyword($param['keyword'])->status($param['status']);

		if ($param['count']) {
			return $model->count();
		}

		$data = $model->getAll($param)->orderBy($param['sort'], $param['order'])->get($param['field'])->toArray();

		return $data ? $data : [];
	}

}