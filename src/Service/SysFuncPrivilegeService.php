<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 17:35
 */
namespace Smart\Service;
use Smart\Models\SysFuncPrivilege;
use Illuminate\Support\Facades\DB;

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

        $oldData = $this->getByFunc( $funcId );
        $p       = [];
        foreach ( $oldData as $item ) {
            $p[] = $item['name'];
        }

        $data['name'] = isset($data['name']) ? $data['name'] : [];
        $needAdd    = array_diff( $data['name'] , $p );
        $needDelete = array_diff( $p , $data['name'] );

        DB::beginTransaction();
        try {
            //如果有要添加的
            if ( ! empty( $needAdd ) ) {
                $addData = [];
                foreach ( $needAdd as $name ) {
                    $addData[] = [
                        'func_id' => $funcId ,
                        'name'    => $name
                    ];

                }
                DB::table('sys_func_privilege')->insert($addData);

            }

            //如果有要删除的
            if ( ! empty( $needDelete ) ) {
                DB::table('sys_func_privilege')
                    ->where( 'func_id' , $funcId )
                    ->whereIn( 'name' ,  $needDelete )
                    ->delete();

            }

            DB::commit();

            return ajax_arr( '成功' , 0 );
        } catch ( \Exception $e ) {
            DB::rollback();

            return ajax_arr( $e->getMessage() , 500 );
        }
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

}