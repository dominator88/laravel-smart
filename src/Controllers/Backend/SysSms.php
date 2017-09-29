<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/25
 * Time: 10:20
 */
namespace Smart\Controllers\Backend;



use Smart\Service\SysSmsService;
use Illuminate\Http\Request;

class SysSms extends Backend {

    /**
     * SysSms constructor.
     */
    public function __construct(Request  $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = SysSmsService::instance();
    }

    //页面入口
    public function index(Request $request) {
        $this->_init( '短信' );

        //uri
        $this->_addParam( 'uri', [


        ] );

        //查询参数
        $this->_addParam( 'query', [
                'keyword'  => $request->input( 'keyword', '' ),
                'status'   => $request->input( 'status', '' ),
                'page'     => $request->input( 'page', 1 ),
                'pageSize' => $request->input( 'pageSize', 10 ),
                'sort'     => $request->input( 'sort', 'id' ),
                'order'    => $request->input( 'order', 'DESC' ),
        ] );


        //其他参数
        $this->_addParam( [
                'defaultRow' => $this->service->getDefaultRow(),
                'status'     => $this->service->status,
                'type'       => $this->service->type
        ] );

        //需要引入的 css 和 js
        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );


        return $this->_displayWithLayout('syssms.index');
    }

    /**
     * 读取
     * @return \Json
     */
    function read(Request $request) {
        $config = [
                'status'   => $request->input( 'status', '' ),
                'keyword'  => $request->input( 'keyword', '' ),
                'page'     => $request->input( 'page', 1 ),
                'pageSize' => $request->input( 'pageSize', 10 ),
                'sort'     => $request->input( 'sort', 'id' ),
                'order'    => $request->input( 'order', 'DESC' ),
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return json( ajax_arr( '查询成功', 0, $data ) );
    }


}