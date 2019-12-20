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
	// public function getMenuByRoles($roleIds, $module) {
	// 	$roleIds = explode(',', $roleIds);
	// 	if ($roleIds == config('backend.superAdminId') || in_array(config('backend.superAdminId'), $roleIds)) {
	// 		$result = $this->getByCond(['isMenu' => 1, 'status' => 1, 'module' => $module]);
			
	// 		//如果是系统管理员
	// 		return $result;
	// 	} else {
	// 		//如果是普通用户
	// 		return $this->_getMenuByRoles($roleIds, $module);
	// 	}
	// }

	public function getMenuByRole($role_id){
		$roots = $this->getRoots();
		$config = [
			'pids' => $roots->pluck('id')->toArray(),
			'module' => $this->getModule(),
			'status' => 1,
			'getAll' => true,
		];
		$menus = $this->getByCond($config);


		$sysRoleService = SysRoleService::instance();

		$func = function(&$menus) use ($role_id,&$func,$sysRoleService){
			foreach($menus as $k => &$menu){
				if(isset($menu['children']) && !empty($menu['children'])){
					$tmp_children = $func($menu['children']);
					unset($menu['children']);
					
					$menus[$k]['children'] = $tmp_children->values();
				}
				//查看是否有菜单展现权限
				$permission_node = $menu->nodeView;
				if(empty($permission_node) || !$sysRoleService->hasPermissionTo($role_id, $permission_node->id)){
					unset($menus[$k]);
				}
			}
			
			return $menus->values();
		};
		$new_arr = collect($menus);
		$new_arr = $func($new_arr);

		return $new_arr;
	}

	public function getMenuByUser($user_id){
		$roots = $this->getRoots();
		$config = [
			'pids' => $roots->pluck('id')->toArray(),
			'module' => $this->getModule(),
			'status' => 1,
			'getAll' => true,
		];
		$menus = $this->getByCond($config);


		$sysUserService = SysUserService::instance();

		$func = function(&$menus) use ($user_id,&$func,$sysUserService){
			foreach($menus as $k => &$menu){
				if(isset($menu['children']) && !empty($menu['children'])){
					$func($menu['children']);
					
				}
				//查看是否有菜单展现权限
				$permission_node = $menu->nodeView;
				if($user_id == config('backend.superAdminId')){
					continue;
				}
				if(empty($permission_node) || !$sysUserService->hasAnyPermission($user_id, (array)$permission_node->id)){
					unset($menus[$k]);
				}
			}
			
			$menus = $menus->values();
		//	dump($menus->toArray());
			return $menus;
		};
		$new_arr = collect($menus);
		$new_arr = $func($new_arr);

		return $new_arr;
	}

	public function getByUri($uri) {
		return $this->getModel()->where('uri', $uri)->first();
	} 

	public function getByCond($param) {
		$default = [
			'field' => ['*'],
			'module' => 'backend',
			'with' => [],
			'isMenu' => '',
			'pids' => [],
			'status' => '',
			'sort' => '',
			'order' => '',
			'page'     => 1 ,
            'pageSize' => 10 ,
			'withPrivilege' => FALSE,
			'key' => self::DEFAULT_KEY,
			'getAll' => FALSE,
		];
		$func = function (&$arr) use (&$func){
          foreach($arr as &$val){
		//	$val->nodeFunc; 
		
			$val->node;
			if($val->extend){
				$val->extend_name = $val->extend->extend_name;
				$val->extend_path = $val->extend->extend_path;
				$val->extend_component = $val->extend->extend_component;
				$val->extend_notCache = $val->extend->extend_notCache;
				$val->extend_showAlways = $val->extend->extend_showAlways;
			}
			
            if(isset($val['children'])){

              if( empty($val['children'])){
                unset($val['children']);
              }else{
                $func($val['children']);
              }
            }
		  }
          return $arr;
          
        };

		$param = extend( $default , $param );
		if($param['pids']){
			$model = $this->getModel()->whereIn('id',$param['pids']);
		}else{
			$model = $this->getModel();
		}
		

		if($param['with']){
			$model = $model->with($param['with']);
		}

        if($param['module']){
        	$model = $model->whereIn('module',(array)$param['module']);
		}

		if($param['sort']){
			$model = $model->orderBy($param['sort'],$param['order']);
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
	// private function _getMenuByRoles($roleIds) {
	// 	$roots = $this->service->getRoots();
	// 	$config = [
	// 		'pids' => $roots->pluck('id')->toArray(),
	// 		'module' => $this->getModule(),
	// 		'status' => 1,
	// 		'getAll' => true,
	// 	];
	// 	$menus = $this->getByCond($config);

	// 	$user = Auth::user();
	// 	$sysRoleService = SysRoleService::instance();

	// 	$func = function(&$menus) use ($user,&$func,$sysRoleService){
	// 		foreach($menus as $k => $menu){
	// 			if(isset($menu['children']) && !empty($menu['children'])){
	// 				$func($menu['children']);
	// 			}
	// 			//查看是否有菜单展现权限
	// 			$permission_node = $menu->nodeView;
	// 			$sysRoleService->hasPermissionTo($roleId, $permission_node);
	// 			if(!$user->can($menu['id'].'.func.read')){
	// 				unset($menus[$k]);
	// 			}
	// 		}
	// 		return $menus;
	// 	};
	// 	$new_arr = collect($menus);
	// 	$new_arr = $func($new_arr);

	// 	return $new_arr;



		/*$key = self::DEFAULT_KEY;

		$data = DB::table('sys_func AS f')
			->where('f.is_menu', 1)
			->where('f.status', 1)
			->where('f.module', "$module")
			->whereIn('rp.role_id', $roleIds)
			->where('fp.name', "read")
			->leftJoin('sys_func_privilege AS fp', 'fp.func_id', '=', 'f.id')
			->leftJoin('sys_role_permission AS rp', 'rp.privilege_id', '=', 'fp.id')
			->orderBy('f.level', 'ASC')->orderBy('f.sort', 'ASC')->get(['f.id', 'f.sort', 'f.pid', 'f.name', 'f.icon', 'f.uri', 'f.level'])->toArray();

		$result = [];
		$index = [];
		$func_ids = [];

		foreach ($data as $row) {
			if (in_array($row->id, $func_ids)) {
				continue;
			}

			if ($row->pid == 0) {
				$result[$row->id] = get_object_vars($row);
				$index[$row->id] = &$result[$row->id];
			} else {
				$index[$row->pid][$key][$row->id] = get_object_vars($row);

				$index[$row->id] = &$index[$row->pid][$key][$row->id];
			}
			$func_ids[] = $row->id;
		}

		return $this->treeToArray($result, self::DEFAULT_KEY);*/
	//}

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


	//获取当前模块下的菜单列表	
	public function getPermission($params){
		$sysFuncs = $this->getModel()->where('module',$params['module'])->where('pid',0)->get();

		$func = function($sysFuncs) use(&$func){

			foreach($sysFuncs as $sysFunc){
			//	$sysFunc->nodeFunc;
				$sysFunc->node;
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
				'sort' => $data['sort'],
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

	//获取根节点组
	public function getRoots(){
		return $this->getModel()->where('module',$this->getModule())->where('pid', 0)->get();
	}

}