<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 17:35
 */
namespace Smart\Service;
use Facades\Smart\Service\ServiceManager;
use Smart\Models\SysFuncPrivilege;
use Illuminate\Support\Facades\DB;
use Smart\Service\SysFuncService;
use Smart\Service\SysPermissionNodeService;

class SysFuncPrivilegeService extends BaseService{

    use \Smart\Traits\Service\TreeTable,\Smart\Traits\Service\Instance;

    protected $model_class = SysFuncPrivilege::class;

    public $name = [
        'read'   => '查看' ,
        'create' => '创建' ,
        'update' => '更新' ,
        'delete' => '删除'
    ];

    //默认操作
    public $default = [
        [ 'sort' => '10' , 'name' => 'read' ] ,
        [ 'sort' => '20' , 'name' => 'create' ] ,
        [ 'sort' => '30' , 'name' => 'update' ] ,
        [ 'sort' => '40' , 'name' => 'delete' ] ,
    ];

    //操作别名
    public $alias = [
        'read'   => [ 'index' , 'read' , 'get' , 'search' , 'load' , 'download' , 'export' , 'preview' ] ,
        'create' => [ 'insert' , 'create' , 'add' , 'upload' , 'post' , 'import' , 'copy' ] ,
        'update' => [ 'update' , 'set' , 'reset' , 'save' , 'send' , 'change' , 'send' ] ,
        'delete' => [ 'destroy' , 'delete' , 'remove' ] ,
    ];

    public function getDefaultRow(){
        
    }


    public function getByCond($param){
        $default = [
            'field' => [ '*' ],
            'module'=> 'backend',
            'isMenu'=> '',
            'pid'   => 0 ,
            'status'=> '',
            'withPrivilege' => FALSE ,
            'key'   => self::DEFAULT_KEY ,
        ];
        $param = extend( $default , $param);

        $data = $this->getModel()
            ->where(function ($query) use ($param) {
                if( $param['status'] !== '') {
                    $query->where('status',$param['status']);
                }
            })
            ->where(function ($query) use ($param) {
                if( $param['module'] !== ''){
                    $query->where('module',$param['module']);
                }
            })
            ->where(function ($query) use ($param) {
                if($param['isMenu'] !== ''){
                    $query->where('is_menu',$param['isMenu']);
                }
            })
            ->orderBy( 'level', 'ASC')
            ->orderBy( 'sort', 'ASC')
            ->get()
            ->toArray();

        if( $param['withPrivilege']){
            $data = $this->withPrivilege($data);
        }

        $result = [];
        $index = [];

        foreach( $data as $row){
            if( $row['pid'] == $param['pid']){
                $result[ $row['id']] = $row;
                $index[ $row['id']] = & $result[ $row['id']];
            }else{
                $index[ $row['pid']][ $param['key'] ][ $row['id'] ] = $row;
                $index[ $row['id'] ] = &$index[ $row['pid'] ][ $param['key'] ][ $row['id'] ];
            }
        }
        $tree_data = $this->treeToArray( $result , $param['key'] );
        return $tree_data;
    }

    /**
     * 更新
     *
     * @param $funcId
     * @param $data
     *
     * @return array
     */
    public function updateByFunc( $funcId  , $data = [] ) {
        $sysFunc = ServiceManager::make(SysFuncService::class);
        $sysFunc = $sysFunc->findById($funcId);
        $new_nodes = $data['node_id'];
        //旧
        $oldPrivilege = $sysFunc->privilege;
        $old_nodes = array_filter($oldPrivilege->pluck('node_id')->toArray());

        //获取两个数组交集 //不需要动的记录
        $interset_arr = array_intersect($old_nodes,$new_nodes);

        //清除老数组交集外的差集数组
        $oldPrivilege_del = array_diff($old_nodes,$interset_arr);
        //需要求权限结算对应的权限也删除掉
        

        $this->getModel()->whereIn('node_id',$oldPrivilege_del)->delete();
        //新增新记录
        $newPrivilege_add = array_diff($new_nodes, $interset_arr);

        $sysPermissionNodeService = ServiceManager::make(SysPermissionNodeService::class);
        $nodes = $sysPermissionNodeService->getByIds($newPrivilege_add);
        $add_privilege = [];
        foreach($nodes as $node){
            $data = [
                'name' => $node->symbol,
                'node_id' => $node->id,
            ];
            array_push($add_privilege,$data);
        }
        
        $sysFunc->privilege()->createMany($add_privilege);

        return ajax_arr('成功',0);
    }

    /**
     * 根据功能取权限
     *
     * @param $funcId
     *
     * @return array
     */
    public function getByFunc( $funcId ) {
        $data = $this->getModel()->where( 'func_id' , $funcId )->get()->toArray();

        return $data ? $data : [];
    }

    public function getByFuncs( $funcIds){
        $data = $this->getModel()->whereIn('func_id' , $funcIds)->get()->toArray();
        $newData = [];
        foreach ( $data as $item ) {
            $newData[ $item['func_id'] ][] = $item;
        }

        return $newData;
    }

    public function syncPermissions($roleId,$privileges){
        $sysRoleService = ServiceManager::make(SysRoleService::class );
        $sysRole = $sysRoleService->findById($roleId);
        $privileges = $this->getModel()->whereIn('id',$privileges)->get();
        $permissions = [];
        foreach($privileges as $privilege){
            array_push($permissions, $privilege->node->permission->id);
        }
       
        $sysRole->role->syncPermissions($permissions);

        return true;
    }

    public function getPermissions($funcIds){
        $funcs = $this->getModel()->whereIn('id',$funcIds)->get();
        $permissions = [];
        foreach($funcs as $func){
            if(isset($func->node)){
                array_push($permissions, $func->node->permission_id);
            }
        }
        return $permissions;
    }

}