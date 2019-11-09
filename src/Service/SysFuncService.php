<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 17:35
 */
namespace Smart\Service;
use Illuminate\Support\Facades\DB;
use Smart\Models\SysFunc; 
use Facades\Smart\Service\ServiceManager;
use Smart\Service\SysRoleService;
use Illuminate\Support\Facades\Auth;

class SysFuncService extends BaseService {

	use \Smart\Traits\Service\TreeTable,\Smart\Traits\Service\Instance;

	protected $model_class = SysFunc::class;

	const DEFAULT_KEY = 'children';

	public $isFunc = [
		0 => '否',
		1 => '是',
	];

	public $isMenu = [
		0 => '否',
		1 => '是',
	];

	//状态
	public $status = [
		0 => '禁用',
		1 => '启用',
	];

	public $privilege = null;



	//默认行
	public function getDefaultRow() {
		return [
			'sort' => '99',
			'module' => 'backend',
			'isMenu' => '1',
			'isFunc' => '0',
			'color' => 'default',
			'name' => '',
			'icon' => '',
			'uri' => '',
			'desc' => '',
			'level' => '1',
			'status' => '1',
		];
	}

	/**
	 * 根据角色取菜单    取名错误,等废弃
	 *
	 * @param $roleIds
	 * @param $module
	 *
	 * @return array
	 */
	public function getMenuByRoles($roleIds, $module) {
		$roleIds = explode(',', $roleIds);
		if ($roleIds == config('backend.superAdminId') || in_array(config('backend.superAdminId'), $roleIds)) {
			$result = $this->getByCond(['isMenu' => 1, 'status' => 1, 'module' => $module]);
			
			//如果是系统管理员
			return $result;
		} else {
			//如果是普通用户
			return $this->_getMenuByRoles($roleIds, $module);
		}
	}

	public function getMenuByRole($roleIds, $module){
		return $this->_getMenuByRoles($roleIds, $module);
	}

	public function getByUri($uri) {
		return $this->getModel()->where('uri', $uri)->first();
	} 

	public function getByCond($param) {
		$default = [
			'field' => ['*'],
		//	'module' => 'backend',
			'with' => [],
			'isMenu' => '',
			'pid' => 0,
			'status' => '',
			'page'     => 1 ,
            'pageSize' => 10 ,
			'withPrivilege' => FALSE,
			'key' => self::DEFAULT_KEY,
			'getAll' => FALSE,
		];
		$func = function (&$arr) use (&$func){
          foreach($arr as &$val){
			$val->nodeFunc;
			if($val->extend){
				$val->extend_name = $val->extend->extend_name;
				$val->extend_path = $val->extend->extend_path;
				$val->extend_component = $val->extend->extend_component;
				$val->extend_notCache = $val->extend->extend_notCache;
				$val->extend_showAlways = $val->extend->extend_showAlways;
			}
			
            if(isset($val['children'])){

              if( $val->has('children') && $val->children->isNotEmpty()){
				$func($val['children']);
              }else{
                unset($val->children);
              }
            }
          }
          return $arr;
          
        };

        $param = extend( $default , $param );
		$model = $this->getModel()->where('pid',$param['pid']);
		 
		if($param['with']){
			$model = $model->with($param['with']);
		}

        if($param['module']){
        	$model = $model->whereIn('module',(array)$param['module']);
        }
    //    echo $model->find(1)->level;
        if ( isset($param['count']) && $param['count'] ) {
            return $model->count();
        }

        if($param['getAll'] === FALSE){
          $model = $model->get()->forPage($param['page'] , $param['pageSize'])->values();
        }else{
          $model = $model->get()->values();
		}
		
        $data = $model;

        $data = $func($data);
        

        return $data ;
	}

	/**
	 * 查找除非超级管理员的菜单
	 *
	 * @param $roleIds
	 * @param $module
	 *
	 * @return array
	 */
	private function _getMenuByRoles($roleIds, $module) {
		$config = [
			'pid' => 0,
			'module' => ucfirst($module),
			'status' => 1,
			'getAll' => true,
		];
		$menus = $this->getByCond($config);

		$user = Auth::user();

		$func = function(&$menus) use ($user,&$func){
			foreach($menus as $k=>$menu){
				if($menu->has('children') && $menu->children->isNotEmpty() ){
					$func($menu['children']);
				}else{
					unset($menu->children);
				}
				if(!$user->can($menu['id'].'.func.read')){
					unset($menus[$k]);
				}
			}
			return $menus;
		};
		$new_arr = collect($menus);
		$new_arr = $func($new_arr);

		return $new_arr;

	}

	/* public function withPrivilege($data) {
		$allId = [];
		foreach ($data as $item) {
			$allId[] = $item['id'];
		}

		$SysFuncPrivilege = SysFuncPrivilegeService::instance();
		$allPrivileges = $SysFuncPrivilege->getByFuncs($allId);

		foreach ($data as &$item) {
			if (isset($allPrivileges[$item['id']])) {
				$item['privilege'] = $allPrivileges[$item['id']];
			} else {
				$item['privilege'] = [];
			}
		}

		return $data;
	} */

	public function getPrivilege($uri) {
		$func = SysFunc::where('uri', $uri)->first();
		if (empty($func)) {
			return false;
		}
		$this->privilege = $func;
		return true;
	}

	public function accept(SysUserService $sysUserService) {

		$klass = get_called_class();
		preg_match('#([^\\\\]+)$#', $klass, $extract);
		$method = 'visit' . $extract[1];

		SysUserService::macro($method, function (SysFuncService $sysFuncService) {
			if ($this->user->id == config('backend.superAdminId')) {
				return true;
			}
			$roles = $this->user->sysRole;
			foreach ($roles as $k => $role) {
				foreach ($role->rolePermission as $key => $privilege) {
					if ($privilege->uri == $sysFuncService->privilege->uri) {
						return true;
					}
				}

			}
			return false;
		});

		return $sysUserService->$method($this);

	}

	public function getSymbol($id){

		$sysFunc = $this->getModel()->find($id);

		if(!empty($sysFunc)){
			$uri_arr = explode('/',strtolower($sysFunc->uri));
			
			if(count($uri_arr) == 3){
				return $uri_arr[0].'.'.$uri_arr[1];
			}
		}
		return false;
	}

	public function findById($id){
		return $this->getModel()->find($id);
	}


		
	public function getPermission($params){
		$sysFuncs = $this->getModel()->where('module',$params['module'])->where('pid',0)->get();

		$func = function($sysFuncs) use(&$func){

			foreach($sysFuncs as $sysFunc){
				if(isset($sysFunc->children) && $sysFunc->children->count() > 0){
					$func($sysFunc->children);
				}
			}
		};	
		$func($sysFuncs);
		return $sysFuncs;
	}


	/**
	 *
	 * 添加数据
	 *
	 * @param $data
	 *
	 * @return array
	 */
	public function insert( $data ) {
		try {
			if ( empty( $data ) ) {
				throw new \Exception( '数据不能为空' );
			}

			$data_base = [
				'module' => $data['module'],
				'is_menu' => $data['is_menu'],
				'name' => $data['name'],
				'icon' => $data['icon'],
				'uri' => $data['uri'],
				'status' => $data['status'],
				'pid' => $data['pid'],
			];
			$data_base['level'] = $this->getLevel( $data['pid'] );
			$id            = $this->getModel()->insertGetId( $data_base );

			if($data_base['module'] !== 'backend'){
			//更新到扩展表中
				$data_extend = [
				//	'func_id' => $id,
					'extend_name' => $data['extend_name'],
					'extend_path' => $data['extend_path'],
					'extend_component' => $data['extend_component'],
					'extend_notCache' => $data['extend_notCache'],
					'extend_showAlways' => $data['extend_showAlways'],
				];
				$sysFuncExtendService = SysFuncExtendService::instance();
				$sysFuncExtendService->updateOrCreate($data_extend, $id);
			}
			return ajax_arr( '创建成功', 0, [ 'id' => $id ] );
		} catch ( \Exception $e ) {
			return ajax_arr( $e->getMessage(), 500 );
		}
	}
	
	/**
	 * 根据ID 更新数据
	 *
	 * @param $id
	 * @param $data
	 *
	 * @return array
	 */
	public function update( $id, $data ) {
		try {
			if ( $data['pid'] == $id ) {
				throw new \Exception( '不能选自己做上级' );
			}

			$data_base = [
				'module' => $data['module'] ?? 'backend',
				'is_menu' => $data['is_menu'],
				'name' => $data['name'],
				'icon' => $data['icon'],
				'uri' => $data['uri'],
				'status' => $data['status'],
				'pid' => $data['pid'],
			];
			$data_base['level'] = $this->getLevel( $data['pid'] );
			$rows          = $this->getModel()->where( 'id', $id )->update( $data_base );
			//
			//更新到扩展表中
			if($data_base['module'] !== 'backend'){
				$data_extend = [
				//	'func_id' => $id,
					'extend_name' => $data['extend_name'],
					'extend_path' => $data['extend_path'],
					'extend_component' => $data['extend_component'],
					'extend_notCache' => $data['extend_notCache'],
					'extend_showAlways' => $data['extend_showAlways'],
				];
				$sysFuncExtendService = SysFuncExtendService::instance();
				$sysFuncExtendService->updateOrCreate( $data_extend,$id);
			}
			if ( $rows == 0 ) {
				return ajax_arr( "未更新任何数据", 0 );
			}
			
			return ajax_arr( "更新成功", 0 );
		} catch ( \Exception $e ) {
		//	return ajax_arr( $e->getMessage(), 500 );
			throw $e;
		}
	}
	
	/**
	 * 根据ID 删除数据
	 *
	 * @param $ids //string | array
	 *
	 * @return array
	 */
	public function destroy( $ids ) {
		try {
			//查看是否有下级数据
			$childrenData = $this->getByPid( $ids );
			if ( ! empty( $childrenData ) ) {
				throw new \Exception( '还有下级数据,不能删除' );
			}
			
			//删除数据
			$rows = $this->getModel()->destroy( $ids );
			if ( $rows == 0 ) {
				return ajax_arr( '未删除任何数据', 0 );
			}
			
			return ajax_arr( "成功删除{$rows}行数据", 0 );
		} catch ( \Exception $e ) {
			return ajax_arr( $e->getMessage(), 500 );
		}
	}

}