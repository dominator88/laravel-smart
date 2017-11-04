<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 15:29
 */
namespace Smart\Controllers\Backend;


use Smart\Service\SysAreaService;
use Smart\Service\ServiceManager;
use Illuminate\Http\Request;

class SysArea extends Backend {

    /**
     * SysArea constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = $this->serviceManager->make( SysAreaService::class);
    }

    //页面入口
    public function index(Request $request) {
        $pid = $request->input( 'pid', 0 );

        $title = '区域管理';
        if ( $pid > 0 ) {
            $parentData = $this->service->getById( $pid );
            $title = $parentData['text'] . ' 的下级区域';
        }

        $this->_init( $title );
        //uri
        $this->_addParam( 'uri', [


        ] );

        //查询参数
        $this->_addParam( 'query', [
            'pid'      => $pid,
            'keyword'  => $request->input( 'keyword', '' ),
            'status'   => $request->input( 'status', '' ),
            'page'     => $request->input( 'page', 1 ),
            'pageSize' => $request->input( 'pageSize', 50 ),
            'sort'     => $request->input( 'sort', 'id' ),
            'order'    => $request->input( 'order', 'DESC' ),
            'getAll'    => false,
        ] );


        //其他参数
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow(),
            'status'     => $this->service->status,
        ] );

        //需要引入的 css 和 js


        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );


        return $this->_displayWithLayout('backend::sysarea.index');
    }

    /**
     * 读取
     * @return Json
     */
    public function read(Request $request) {
        $config = [
            'pid'      => $request->input( 'pid', 0 ),
            'status'   => $request->input( 'status', '' ),
            'keyword'  => $request->input( 'keyword', '' ),
            'page'     => $request->input( 'page', 1 ),
            'pageSize' => $request->input( 'pageSize', 50 ),
            'sort'     => $request->input( 'sort', 'id' ),
            'order'    => $request->input( 'order', 'DESC' ),
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return response()->json( ajax_arr( '查询成功', 0, $data ) );
    }




}