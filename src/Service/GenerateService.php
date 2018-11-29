<?php
/**
 * 自动生成代码 Service
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/25
 * Time: 14:46
 */

namespace Smart\Service;

use Facades\Smart\Service\ServiceManager;
use Illuminate\Support\Facades\DB;
use Smart\Service\SysModulesService;

define('SYSTEM_TEMP_BASE_PATH', __DIR__ . '/../../templates/generate/system/');
define('API_TEMP_BASE_PATH', __DIR__ . '/../../templates/generate/api');
define('APP_PATH', app_path());
define('BASE_PATH', base_path());

class GenerateService {

	public $type = [
		'system' => '管理模块',
		'api' => 'API 接口',
	];

	//模块
	public $module = [
		'Backend' => 'Backend',
		'Mp' => 'Mp',

	];

	public $apiVer = [
		'v1' => 'v1',

	];

	var $apiParams = [
		'api_token' => 'api_token (用户Token)',
		'phone' => 'phone (手机号)',
		'status' => 'status (状态)',
		'merId' => 'mer_id (机构ID)',
		'orderId' => 'orderId (订单ID)',
		'goodsId' => 'goodsId (商品ID)',
		'page' => 'page (页码)',
		'pageSize' => 'pageSize (每面行数)',
	];

	var $apiAuthUser = [
		0 => '否',
		1 => '是',
	];

	//表类型
	var $tableType = [
		'grid' => 'Grid',
		'treeGrid' => 'Tree Grid',
	];

	//视图类型
	var $viewType = [
		'portlet' => '层 (Portlet)',
		'modal' => '弹窗 (Modal)',
	];

	//文件存储路径 		ucfirst()
	protected $filePath = [
		//   'model'      => base_path('common/model/{func}.php'),
		'model' => APP_PATH . '/Models/{func}.php',
		'service' => APP_PATH . '/Service/{func}Service.php',
		'controller' => APP_PATH . '/{moduleLower}/controllers/{func}.php',
		'js' => 'static/js/{moduleLower}/{func}.js',
		//   'view'       => BASE_PATH . '/resources/views/{module}/{funcLower}/index.html',
		'view' => APP_PATH . '/{moduleLower}/views/{funcLower}/index.blade.php',
		'api' => APP_PATH . '/Api/Service/',
	];

	//字段识别
	var $fieldIdentify = [
		//上传字段
		'upload' => ['icon', 'icon_2x', 'icon_3x', 'img', 'image', 'pic'],
		//editor
		'editor' => ['content'],
		'tableType' => ['pid', 'level'],
		//radio
		'radio' => ['status', 'type', 'catalog'],
		//select
		'select' => ['pid'],
		//form 排除字段
		'formExclusion' => ['id', 'mer_id', 'created_at', 'level', 'updated_at'],
		//th 排除字段
		'thExclusion' => ['mer_id', 'desc', 'content', 'level'],
		//th 格式化方法
		'thFormatter' => [
			'catalog' => 'data-formatter="formatCatalog"',
			'icon' => 'data-formatter="formatIcon"',
			'status' => 'data-formatter="formatStatus"',
			'type' => 'data-formatter="formatType"',
			'created_at' => 'data-formatter="formatDatetime"',
		],
		//th 宽度
		'thWidth' => [
			'id' => '40',
			'sort' => '40',
			'text' => '200',
			'name' => '200',
			'title' => '200',
			'created_at' => '180',
		],
	];

	//模板
	var $systemTemps = [
		'model' => 'model.txt',
		'service' => 'service.txt',
		'controller' => 'controller.txt',
		'view' => 'view.txt',
		'js' => 'js.txt',

	];

	var $systemComponents = [
		'traits' => [
			'instanceTrait' => 'component/traits/instance_trait.txt',
		],
		'editor' => [
			'controller' => [
				'editorDecode' => 'component/editor/controller/editor_decode.txt',
				'editorJs' => 'component/editor/controller/editor_js.txt',
				'editorUri' => 'component/editor/controller/editor_uri.txt',
			],
			'js' => [
				'editorClear' => 'component/editor/js/editor_clear.txt',
				'editorInit' => 'component/editor/js/editor_init.txt',
				'editorReload' => 'component/editor/js/editor_reload.txt',
				'editorUploadUri' => 'component/editor/js/editor_upload_uri.txt',
			],
		],
		'field' => [
			'view' => [
				'editor' => 'component/field/view/editor.txt',
				'input' => 'component/field/view/input.txt',
				'radio' => 'component/field/view/radio.txt',
				'select' => 'component/field/view/select.txt',
				'select2' => 'component/field/view/select2.txt',
				'upload' => 'component/field/view/upload.txt',
			],
		],
		'select2' => [
			'controller' => [
				'select2Css' => 'component/select2/controller/select2_css.txt',
				'select2Js' => 'component/select2/controller/select2_js.txt',
				'select2Uri' => 'component/select2/controller/select2_uri.txt',
			],
			'js' => [
				'select2Clear' => 'component/select2/js/select2_clear.txt',
				'select2Init' => 'component/select2/js/select2_init.txt',
				'select2Set' => 'component/select2/js/select2_set.txt',
			],
		],
		'tableType' => [
			'controller' => [
				'grid' => [
					'gridJs' => 'component/table_type/controller/grid_js.txt',
					'gridRead' => 'component/table_type/controller/grid_read.txt',
				],
				'treeGrid' => [
					'treeGridJs' => 'component/table_type/controller/tree_grid_js.txt',
					'treeGridRead' => 'component/table_type/controller/tree_grid_read.txt',
				],
			],
			'js' => [
				'grid' => [
					'gridInit' => 'component/table_type/js/grid_init.txt',
					'gridPlugin' => 'component/table_type/js/grid_plugin.txt',
					'gridId' => 'component/table_type/js/grid_id.txt',
				],
				'treeGrid' => [
					'treeGridInit' => 'component/table_type/js/tree_grid_init.txt',
					'treeGridPlugin' => 'component/table_type/js/tree_grid_plugin.txt',
					'treeGridId' => 'component/table_type/js/tree_grid_id.txt',
				],
			],
			'service' => [
				'grid' => [
					'grid' => 'component/table_type/service/grid.txt',
					'gridTrait' => 'component/table_type/service/grid_trait.txt',
				],
				'treeGrid' => [
					'treeGrid' => 'component/table_type/service/tree_grid.txt',
					'treeGridTrait' => 'component/table_type/service/tree_grid_trait.txt',
				],
			],
			'view' => [
				'grid' => 'component/table_type/view/grid.txt',
				'treeGrid' => 'component/table_type/view/tree_grid.txt',
			],
		],
		'upload' => [
			'controller' => [
				'uploadJs' => 'component/upload/controller/upload_js.txt',
				'uploadUri' => 'component/upload/controller/upload_uri.txt',
				'uploadParam' => 'component/upload/controller/upload_param.txt',
			],
			'js' => [
				'uploadButton' => 'component/upload/js/upload_button.txt',
				'uploadPreviewClear' => 'component/upload/js/upload_preview_clear.txt',
				'uploadPreviewSet' => 'component/upload/js/upload_preview_set.txt',
			],
		],
		'viewType' => [
			'js' => [
				'modal' => 'component/view_type/js/modal.txt',
				'portlet' => 'component/view_type/js/portlet.txt',
			],
			'view' => [
				'modal' => 'component/view_type/view/modal.txt',
				'portlet' => 'component/view_type/view/portlet.txt',
			],
		],
	];

	var $apiTemp = [
		'tmp' => API_TEMP_BASE_PATH . '/api.txt',
		'authUser' => API_TEMP_BASE_PATH . '/auth_user.txt',
	];

	private static $instance;

	public static function instance() {
		if (self::$instance == NULL) {
			self::$instance = new GenerateService();
		}
		self::$instance->_init();
		return self::$instance;
	}

	private function _init() {
		//初始化Module
		$sysModulesService = ServiceManager::make(SysModulesService::class);
		$modules = $sysModulesService->getByCond(['status' => 1]);
		
		foreach ($modules as $m) {
			$this->module = array_merge($this->module, [ucfirst($m['symbol']) => $m['symbol']]);
		}

		//初始化api版本号
		$apiVersion = config('backend.api.apiVersion');
		$this->apiVer = [$apiVersion => $apiVersion];

	}

	/**
	 * 取所有表
	 * @return \Collection
	 */
	public function getTables() {
		$data = DB::table('information_schema.tables')
			->where('TABLE_SCHEMA', config('database.connections.mysql.database'))
			->get(['table_name as tableName'])->toArray();

		return $data ? $data : [];
	}

	private function getSystemComponents() {
		$component = [];

		foreach ($this->systemComponents as $key => $item) {
			$component[] = $key;
		}

		return $component;
	}

	private function getTableField($tableName) {

		$db = DB::table('information_schema.columns')
			->where('TABLE_NAME', $tableName)
			->where('TABLE_SCHEMA', config('database.connections.mysql.database'));

		return $db->get([
			'COLUMN_NAME AS fieldName',
			'DATA_TYPE AS fieldType',
			'COLUMN_DEFAULT AS fieldDefault',
			'COLUMN_COMMENT AS fieldComment',
		])->toArray();
	}

	//取单个表信息
	public function getSystemInfo($tableName, $module, $returnFieldInfo = FALSE) {
		$fieldInfo = $this->getTableField($tableName);

		if (!$fieldInfo || empty($fieldInfo)) {
			return ajax_arr('表不存在', 500);
		}

		$components = $this->getSystemComponents();

		$identify = [];

		foreach ($components as $component) {
			if (isset($this->fieldIdentify[$component])) {
				foreach ($fieldInfo as $item) {
					if (in_array($item->fieldName, $this->fieldIdentify[$component])) {
						$identify[$component] = $item->fieldName;
					}
				}
				if (!isset($identify[$component])) {
					$identify[$component] = '';
				}
			} else {
				$identify[$component] = '';
			}
		}

		if (!empty($identify['tableType'])) {
			$identify['tableType'] = 'treeGrid';
		} else {
			$identify['tableType'] = 'grid';
		}
		$identify['viewType'] = 'portlet';
		unset($identify['field']);

		$func = $this->getFuncName($tableName);

		$replaceData = [
			'tableName' => $tableName,
			'func' => $func,
			'funcLower' => strtolower($func),
			'funcUcfirst' => ucfirst(strtolower($func)),
			'module' => $this->module[$module],
			'moduleLower' => strtolower($this->module[$module]),
		];

		$filePath = [
			'model' => $this->replaceTmp($this->filePath['model'], $replaceData),
			'service' => $this->replaceTmp($this->filePath['service'], $replaceData),
			'controller' => $this->replaceTmp($this->filePath['controller'], $replaceData),
			'view' => $this->replaceTmp($this->filePath['view'], $replaceData),
			'js' => $this->replaceTmp($this->filePath['js'], $replaceData),
		];

		$fileExists = [];
		//检查是否存在
		foreach ($filePath as $key => $row) {
			$fileExists[$key] = file_exists($row);
		}

		$data = [
			'tableName' => $tableName,
			'module' => $module,
			'func' => $func,
			'funcName' => $func,
			'filePath' => $filePath,
			'fileExists' => $fileExists,
			'components' => $identify,
			//'field_info'  => $field_info,
		];

		if ($returnFieldInfo) {
			$data['fieldInfo'] = $fieldInfo;
		}

		return ajax_arr('查询成功', 0, $data);
	}

	//表名转功能名
	private function getFuncName($tableName) {
		$tmp = explode('_', $tableName);

		$name = '';
		foreach ($tmp as $s) {
			$name .= ucfirst($s);
		}

		return $name;
	}

	//模板替换
	function replaceTmp($tmpContent, $data) {
		foreach ($data as $key => $value) {
			$tmpContent = str_replace('{' . $key . '}', $value, $tmpContent);
		}

		return $tmpContent;
	}

	//生成全部
	public function createSystem($data) {
		$temp = $data['temp'];
		$systemInfo = $this->getSystemInfo($data['tableName'], $data['module'], TRUE)['data'];

		$data['filePath'] = $systemInfo['filePath'];
		$data['func'] = $systemInfo['func'];
		$data['funcName'] = $systemInfo['funcName'];
		$data['fieldInfo'] = $systemInfo['fieldInfo'];
		$data['date'] = date('Y-m-d');

		foreach ($systemInfo['components'] as $c => $v) {
			if (!isset($data['components'][$c])) {
				$data['components'][$c] = '';
			}
		}
		//$data['components'] = extend( $system_info['components'], $data['components'] );

		$ret = ajax_arr('正在创建', 500);

		if ($temp == 'all') {
			$files = 0;
			foreach ($systemInfo['fileExists'] as $key => $value) {
				//文件不存在就创建
				if (!$value) {
					$files++;
					$method = "create" . ucfirst($key);
					$data['temp'] = $key;
					$ret = $this->$method($data);
					if ($ret['code'] != 0) {
						return ajax_arr($ret['msg'], 500);
					}
				}
			}

			if ($files == 0) {
				return ajax_arr("文件都存在了", 0);
			}

			return ajax_arr("创建成功", 0);
		} else {
			$method = "create" . ucfirst($temp);
			$ret = $this->$method($data);

			return $ret;
		}
	}

	private function createComponentTraits($temp, $tempContent, $value){
		$component = 'traits';
		if (!isset($this->systemComponents[$component])) {
			return $tempContent;
		}
		$replaceData = [];
		foreach($this->systemComponents[$component] as $keyword => $filePaths){

			if ($keyword == $value) {
				$replaceData[$keyword] = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths);
			} else {
				$replaceData[$keyword] = '';
			}

		}

		return $this->replaceTmp($tempContent , $replaceData);
	}

	//table_type 组件
	private function createComponentTableType($temp, $tempContent, $value) {
		$component = 'tableType';
		if (!isset($this->systemComponents[$component][$temp])) {
			return $tempContent;
		}

		$replaceData = [];
		foreach ($this->systemComponents[$component][$temp] as $keyword => $filePaths) {
//			echo $filePaths . '----';
			if (is_array($filePaths)) {
				foreach ($filePaths as $key => $filePath) {
					if ($keyword == $value) {
						$replaceData[$key] = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePath);
					} else {
						$replaceData[$key] = '';
					}
				}
			} else {
				if ($keyword == $value) {
					$replaceData[$keyword] = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths);
				} else {
					$replaceData[$keyword] = '';
				}
			}
		}

		return $this->replaceTmp($tempContent, $replaceData);
	}

	private function createComponentViewType($temp, $tempContent, $value) {
		$component = 'viewType';
		if (!isset($this->systemComponents[$component][$temp])) {
			return $tempContent;
		}

		$replaceData = [];
		foreach ($this->systemComponents[$component][$temp] as $keyword => $filePaths) {
			if ($value == $keyword) {
				$replaceData[$keyword] = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths);
			} else {
				$replaceData[$keyword] = '';
			}
		}

		//print_r($replaceData);
		return $this->replaceTmp($tempContent, $replaceData);
	}

	//upload 组件
	private function createComponentUpload($temp, $tempContent, $value, $data) {

		$component = 'upload';
		if (!isset($this->systemComponents[$component][$temp])) {
			return $tempContent;
		}

		$replaceData = [];

		foreach ($this->systemComponents[$component][$temp] as $keyword => $filePaths) {
			if (!empty($data['components'][$component])) {
				$content = '';
				$multi = ['uploadButton', 'uploadPreviewClear', 'uploadPreviewSet'];
				if (in_array($keyword, $multi)) {
					if (!is_array($value)) {
						$values[] = $value;
					} else {
						$values = $value;
					}

					foreach ($values as $v) {
						$field['field'] = $v;
						$content .= $this->replaceTmp(file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths), $field) . "\r";
					}
				} else {
					$content = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths);
				}

				$replaceData[$keyword] = $content;
			} else {
				$replaceData[$keyword] = '';
			}
		}

		//print_r($replaceData);
		return $this->replaceTmp($tempContent, $replaceData);
	}



	private function createComponentEditor($temp, $tempContent, $value, $data) {
		$component = 'editor';
		if (!isset($this->systemComponents[$component][$temp])) {
			return $tempContent;
		}

		$replaceData = [];
		foreach ($this->systemComponents[$component][$temp] as $keyword => $filePaths) {
			if (!empty($data['components'][$component])) {
				$content = '';
				$multi = ['editorDecode', 'editorInit', 'editorClear', 'editorReload'];
				if (in_array($keyword, $multi)) {
					if (!is_array($value)) {
						$value = [$value];
					}
					foreach ($value as $v) {
						$field['field'] = $v;
						$content .= $this->replaceTmp(file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths), $field) . "\r";
					}
				} else {
					$content = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths);
				}

				$replaceData[$keyword] = $content;
			} else {
				$replaceData[$keyword] = '';
			}
		}

		return $this->replaceTmp($tempContent, $replaceData);
	}

	private function createComponentSelect2($temp, $tempContent, $value, $data) {
		$component = 'select2';
		if (!isset($this->systemComponents[$component][$temp])) {
			return $tempContent;
		}

		$replaceData = [];
		foreach ($this->systemComponents[$component][$temp] as $keyword => $filePaths) {
			if (!empty($data['components'][$component])) {
				$replaceData[$keyword] = file_get_contents(SYSTEM_TEMP_BASE_PATH . $filePaths);
			} else {
				$replaceData[$keyword] = '';
			}
		}

		return $this->replaceTmp($tempContent, $replaceData);
	}

	private function createComponentFormField($temp, $components, $field, $comment) {
		$form_content = '';
		if (in_array($field, $this->fieldIdentify['formExclusion'])) {
			return $form_content;
		}

		$field_type = '';
		foreach ($components as $component => $fields) {
			if (empty($fields)) {
				continue;
			}
			if (!is_array($fields)) {
				$fields = [$fields];
			}
			if (in_array($field, $fields)) {
				$field_type = $component;
			}
		}

		if (empty($field_type) && in_array($field, $this->fieldIdentify['radio'])) {
			$field_type = 'radio';
		}

		if (empty($field_type) && in_array($field, $this->fieldIdentify['select'])) {
			$field_type = 'select';
		}
		if (empty($field_type)) {
			$field_type = 'input';
		}

		$field_replace = [
			'field' => $field,
			'comment' => $comment,
		];

		$content = file_get_contents(SYSTEM_TEMP_BASE_PATH . $this->systemComponents['field'][$temp][$field_type]);

		return $this->replaceTmp($content, $field_replace);
	}

	private function saveFile($temp, $filePath, $fileContent) {
		$dir = dirname($filePath);
		if (!file_exists($dir)) {
			mkdir($dir, 0777, true);
			chmod($dir, 0777);
		}

		$ret = file_put_contents($filePath, $fileContent);
		if ($ret === FALSE) {
			return ajax_arr($temp . ' 创建失败', 500);
		}
		chmod($filePath, 0777);

		return ajax_arr($temp . ' 创建成功', 0);
	}

	//创建 model
	private function createModel($data) {
		$temp = $data['temp'];

		//文件路径
		$filePath = $data['filePath'][$temp];
		if (file_exists($filePath)) {
			return ajax_arr($temp . ' 已经存在', 500);
		}

		//替换的数据
		$replaceData = [
			'func' => $data['func'],
			'date' => $data['date'],
			'funcName' => $data['funcName'],
			'tableName' => $data['tableName'],
		];

		//文件模板
		$tempContent = file_get_contents(SYSTEM_TEMP_BASE_PATH . $this->systemTemps[$temp]);

		$fileContent = $this->replaceTmp($tempContent, $replaceData);

		//保存文件
		return $this->saveFile($temp, $filePath, $fileContent);
	}

	//创建 service
	private function createService($data) {
		 $data['components']['traits'] = 'instanceTrait';
		$temp = $data['temp'];
		//文件模板
		$tempContent = file_get_contents(SYSTEM_TEMP_BASE_PATH . $this->systemTemps[$temp]);

		//文件路径
		$filePath = $data['filePath'][$temp];
		if (file_exists($filePath)) {
			return ajax_arr($temp . '已经存在', 500);
		}

		//替换的数据
		$replaceData = [
			'func' => $data['func'],
			'date' => $data['date'],
			'funcName' => $data['funcName'],
			'tableName' => $data['tableName'],
		];

		//字段默认值
		$fieldDefault = [];
		foreach ($data['fieldInfo'] as $item) {
			$val = ($item->fieldDefault == 'CURRENT_TIMESTAMP') ? "date('Y-m-d H:i:s')" : "'{$item->fieldDefault}'";

			$fieldDefault[] = "'{$item->fieldName}' => $val , ";
		}

		$replaceData['fieldDefault'] = implode("\r", $fieldDefault);

		//加载组件
		foreach ($data['components'] as $component => $value) {
			$componentMethod = "createComponent" . ucfirst($component);
			$tempContent = $this->$componentMethod($temp, $tempContent, $value, $data);
		}
		$fileContent = $this->replaceTmp($tempContent, $replaceData);

		//保存文件
		return $this->saveFile($temp, $filePath, $fileContent);
	}

	//创建 controller
	private function createController($data) {
		$temp = $data['temp'];
		//文件模板
		$tempContent = file_get_contents(SYSTEM_TEMP_BASE_PATH . $this->systemTemps[$temp]);

		//文件路径
		$filePath = $data['filePath'][$temp];
		if (file_exists($filePath)) {
			return ajax_arr($temp . '已经存在', 500);
		}

		//替换的数据
		$replaceData = [
			'module' => $data['module'],
			'moduleLower' => strtolower($data['module']),
			'func' => $data['func'],
			'funcLower' => strtolower($data['func']),
			'date' => $data['date'],
			'funcName' => $data['funcName'],
			'tableName' => $data['tableName'],
		];
		
		//加载组件
		foreach ($data['components'] as $component => $value) {
			$componentMethod = "createComponent" . ucfirst($component);

			$tempContent = $this->$componentMethod($temp, $tempContent, $value, $data);
		}
		$fileContent = $this->replaceTmp($tempContent, $replaceData);

		//print_r( $fileContent );
		//保存文件
		return $this->saveFile($temp, $filePath, $fileContent);
	}

	//创建 view
	private function createView($data) {
		$temp = $data['temp'];
		//文件模板
		$tempContent = file_get_contents(SYSTEM_TEMP_BASE_PATH . $this->systemTemps[$temp]);

		//文件路径
		$filePath = $data['filePath'][$temp];
		if (file_exists($filePath)) {
			return ajax_arr($temp . '已经存在', 500);
		}

		//替换的数据
		$replaceData = [
			'module' => $data['module'],
			'moduleLower' => strtolower($data['module']),
			'func' => $data['func'],
			'funcLower' => strtolower($data['func']),
			'date' => $data['date'],
			'funcName' => $data['funcName'],
			'tableName' => $data['tableName'],
		];

		//加载组件
		foreach ($data['components'] as $component => $value) {
			$componentMethod = "createComponent" . ucfirst($component);
			$tempContent = $this->$componentMethod($temp, $tempContent, $value, $data);
		}
		$fileContent = $this->replaceTmp($tempContent, $replaceData);

		$replaceField = [
			'tableTh' => '',
			'formItems' => '',
		];

		foreach ($data['fieldInfo'] as $item) {
			$field = $item->fieldName;
			$comment = $item->fieldComment;
			//th 排除字段
			if (!in_array($field, $this->fieldIdentify['thExclusion'])) {
				$formatter = '';
				if (isset($this->fieldIdentify['thFormatter'][$field])) {
					$formatter = $this->fieldIdentify['thFormatter'][$field];
				}
				$width = '80';
				if (isset($this->fieldIdentify['thWidth'][$field])) {
					$width = $this->fieldIdentify['thWidth'][$field];
				}

				$replaceField['tableTh'] .= '<th width="' . $width . '" data-field="' . $item->fieldName . '" ' . $formatter . '>' .
					$comment . '</th>' . "\r";
			}
			if (!in_array($field, $this->fieldIdentify['formExclusion'])) {
				$replaceField['formItems'] .= $this->createComponentFormField($temp, $data['components'], $field, $comment) . "\r";
			}
		}

		$fileContent = $this->replaceTmp($fileContent, $replaceField);

		//保存文件
		return $this->saveFile($temp, $filePath, $fileContent);
	}

	//创建 js
	private function createJs($data) {
		$temp = $data['temp'];
		//文件模板
		$tempContent = file_get_contents(SYSTEM_TEMP_BASE_PATH . $this->systemTemps[$temp]);

		//文件路径
		$filePath = $data['filePath'][$temp];
		if (file_exists($filePath)) {
			return ajax_arr($temp . '已经存在', 500);
		}

		//替换的数据
		$replaceData = [
			'module' => $data['module'],
			'moduleLower' => strtolower($data['module']),
			'func' => $data['func'],
			'date' => $data['date'],
			'funcName' => $data['funcName'],
			'tableName' => $data['tableName'],
		];

		//加载组件
		foreach ($data['components'] as $component => $value) {
			$componentMethod = "createComponent" . ucfirst($component);
			$tempContent = $this->$componentMethod($temp, $tempContent, $value, $data);
		}
		$fileContent = $this->replaceTmp($tempContent, $replaceData);

		//保存文件
		return $this->saveFile($temp, $filePath, $fileContent);
	}

	//创建Api
	public function createApi($data) {
		$data['directory'] = strtolower($data['directory']);
		$data['name'] = ucfirst(strtolower($data['name']));
		$data['date'] = date('Y-m-d');
		$filePath = $this->filePath['api'] . "{$data['apiVersion']}/{$data['directory']}";
		$filename = $filePath . "/{$data['name']}Service.php";

		if (file_exists($filename)) {
			return ajax_arr("$filename 已经存在", 505);
		}

		if (!file_exists($filePath)) {
			mkdir($filePath, 0777, true) or die('创建失败');
			chmod($filePath, 0777);
		}

		//是否有参数
		$params = '';
		if (isset($data['params'])) {
			foreach ($data['params'] as $param) {
				$params .= file_get_contents(API_TEMP_BASE_PATH . "/params/{$param}.txt") . "\r";
			}
		}
		$data['params'] = $params;

		//是否验证用户
		if ($data['authUser'] == 1) {
			$data['authUser'] = file_get_contents($this->apiTemp['authUser']);
		} else {
			$data['authUser'] = '';
		}

		$fileContent = file_get_contents($this->apiTemp['tmp']);
		$fileContent = $this->replaceTmp($fileContent, $data);

		$ret = file_put_contents($filename, $fileContent);
		if ($ret === FALSE) {
			return ajax_arr("创建Api[ $filename ]失败", 500);
		} else {
			chmod($filename, 0777);

			return ajax_arr("创建Api[ $filename ]成功", 0);
		}
	}

	public function deleteSystemFile($module, $tableName, $temp) {
		$replaceData = [
			'module' => $module,
			'moduleLower' => strtolower($module),
			'func' => $this->getFuncName($tableName),
			'funcLower' => strtolower($this->getFuncName($tableName)),
			'funcUcfirst' => ucfirst(strtolower($this->getFuncName($tableName))),
			'tableName' => $tableName,
		];

		$filePath = $this->replaceTmp($this->filePath[$temp], $replaceData);

		if (file_exists($filePath)) {
			unlink($filePath);

			return ajax_arr("删除[$temp]文件成功", 0);
		}

		return ajax_arr("[$temp] 文件不存在", 500);
	}
}
