<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 10:18
 */
namespace Smart\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class Index extends Backend {
	
	protected $autoload_service = 0;

	public function __construct(Request $request) {
		parent::__construct($request);

	}

	public function index(Request $request) {
		$this->_init('首页');
 
		$this->_addJsLib('node_modules/waypoints/lib/jquery.waypoints.min.js');
		$this->_addJsLib('node_modules/jquery.counterup/jquery.counterup.min.js');
		$this->_addJsLib('node_modules/echarts/dist/echarts.min.js');

		$stat = cache('stat');
		if (!$stat) {
			$stat = [
				'articles' => DB::table('mer_articles')->count(),
				'users' => DB::table('mer_user')->count('id'),
				'api' => DB::table('sys_api_log')->whereTime('created_at', '>', date('Y-m-d'))->count(),
				'download' => 0,
			];
			cache('stat', $stat, 300);
		}

		$charts = $this->_getCharts($stat);

		$this->_addData('stat', $stat);
		$this->_addParam('charts', $charts);

		return $this->_displayWithLayout('backend::index/index');

	}

	/**
	 * 获取图表数据
	 *
	 * @param $stat
	 *
	 * @return array
	 */
	private function _getCharts($stat) {
		$data = DB::table('sys_statistics')->orderBy('created_at', 'ASC')->take(29)->get()->toArray();

		$period = [];
		$users = [];
		$api = [];
		foreach ($data as $item) {
			$item = get_object_vars($item);
			$period[] = substr($item['created_at'], 5, 5);
			$users[] = $item['users_today'];
			$api[] = $item['api'];
		}

		$period[] = date('m-d');
		$users[] = DB::table('mer_user')->whereDate('reg_at', '>', date('Y-m-d'))->count();
		$api[] = $stat['api'];

		return [
			'users' => [
				'period' => $period,
				'data' => $users,
			],
			'api' => [
				'period' => $period,
				'data' => $api,
			],
		];
	}

}