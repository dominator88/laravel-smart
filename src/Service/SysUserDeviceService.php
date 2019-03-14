<?php namespace Smart\Service;
/**
 * SysUserDevice Service
 *
 * @author MR.Z <zsh2088@gmail.com>
 * @version 2.0 2018-06-18
 */

use Smart\Models\SysUserDevice;
use Smart\Service\BaseService;

class SysUserDeviceService extends BaseService {

	//引入 GridTable trait
	use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

	protected $model_class = SysUserDevice::class;
	//状态
	public $status = [
		0 => '禁用',
		1 => '启用',
	];

	public $for_test = [
		0 => '否',
		1 => '是',
	];

	

	

	//取默认值
	function getDefaultRow() {
		return [
			'api_version' => '',
			'app_version' => '',
			'created_at' => '',
			'device' => '',
			'device_os_version' => '',
			'for_test' => '0',
			'id' => '',
			'registration_id' => '',
			'token' => '',
			'updated_at' => '',
			'user_id' => '',
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

	//生成token
	public function generateToken(){

	}

}