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
        $model = $this->getModel()->where('pid',0);
        if($param['module']){
        	$model = $model->whereIn('module',(array)$param['module']);
        }
    //    echo $model->find(1)->level;
        if ( isset($param['count']) && $param['count'] ) {
            return $model->count();
        }

        if($param['getAll'] === FALSE){
          $model = $model->with('children.privilege','privilege')->get()->forPage($param['page'] , $param['pageSize'])->values();
        }else{
          $model = $model->with('children.privilege','privilege')->get()->values();
        }
        $data = $model->toArray();



        $data = $func($data);
        

        return $data ? $data : [];
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
		$key = self::DEFAULT_KEY;

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

		return $this->treeToArray($result, self::DEFAULT_KEY);
	}

	public function withPrivilege($data) {
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
	}

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
		
		return $sysFuncs;
	}

}