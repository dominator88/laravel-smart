<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/25
 * Time: 14:22
 */
namespace Smart\Controllers\Backend;



use Smart\Service\SysMailService;
use Illuminate\Http\Request;
use Facades\Smart\Service\ServiceManager;
class SysMail extends Backend {



    //页面入口
    public function index(Request $request) {
        $this->_init( '邮件' );

        //uri
        $this->_addParam( 'uri' , [
            'send' => full_uri( 'backend/sysmail/send' )

        ] );

        //查询参数
        $this->_addParam( 'query' , [
            'keyword'  => $request->input( 'keyword' , '' ) ,
            'status'   => $request->input( 'status' , '' ) ,
            'page'     => $request->input( 'page' , 1 ) ,
            'pageSize' => $request->input( 'pageSize' , 10 ) ,
            'sort'     => $request->input( 'sort' , 'id' ) ,
            'order'    => $request->input( 'order' , 'DESC' ) ,
        ] );

        //其他参数
        $this->_addParam( [
            'defaultRow' => $this->service->getDefaultRow() ,
            'status'     => $this->service->status ,
            'type'       => $this->service->type
        ] );

        //需要引入的 css 和 js
        $this->_addJsLib( 'static/plugins/dmg-ui/TableGrid.js' );

        return $this->_displayWithLayout('backend::sysmail.index');
    }

    /**
     * 读取
     * @return \Json
     */
    public function read(Request  $request) {
        $config = [
            'status'   => $request->input( 'status' , '' ) ,
            'keyword'  => $request->input( 'keyword' , '' ) ,
            'page'     => $request->input( 'page' , 1 ) ,
            'pageSize' => $request->input( 'pageSize' , 10 ) ,
            'sort'     => $request->input( 'sort' , 'id' ) ,
            'order'    => $request->input( 'order' , 'DESC' ) ,
        ];

        $data['rows']    = $this->service->getByCond( $config );
        $config['count'] = TRUE;
        $data['total']   = $this->service->getByCond( $config );

        return json( ajax_arr( '查询成功' , 0 , $data ) );
    }

    public function send(Request  $request) {
        $id = $request->input('id');
        $result = $this->service->sendById( $id );

        return json( $result );
    }
}