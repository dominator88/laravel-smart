<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 21:07
 */
namespace App\Api;


use Smart\Service\ApiService;
use Illuminate\Http\Request;


class Index {

    public $api = NULL;

    public function __construct() {
        $this->api        = ApiService::instance();
        $this->api->debug = FALSE;
    }

    public function index(Request $request , $version , $directory , $action = 'index' ) {

        $header = [
            'timestamp'       => $request->header( 'timestamp' ) ,
            'signature'       => $request->header( 'signature' ) ,
            'device'          => $request->header( 'device' ) ,
            'deviceOsVersion' => $request->header( 'device-os-version' ) ,
            'appVersion'      => $request->header( 'app-version' ) ,
            'apiVersion'      => $request->input('version') ,
        ];
        //取api
        $api = $this->api;

        $params = $request->all();
        //取时间戳
        $params['timestamp'] = $request->header( 'timestamp' ) ;

        $params = array_merge( $params , $header );
        $result = $this->response( $version , $directory , $action , $params );
        $api->log( '请求结束' );

        return json( $result );
    }

    /**
     * 响应辅助函数
     *
     * @param $version
     * @param $directory
     * @param $action
     * @param $params
     *
     * @return array
     */
    private function response( $version , $directory , $action , $params ) {

        $action  = ucfirst( $action );
        $version = strtolower( $version );
        $class   = '\\App\\Api\\Service\\' . $version . '\\' . $directory . '\\' . $action . 'Service';
        $this->api->log( 'service file' , $class );

        //检查是否存在响应文件
        if ( ! class_exists( $class ) ) {
            return $this->api->getError( 404 );
        }

        //初始化响应类
        $instance = $class::instance( $params );
        //检查请求方式
        if ( ! $this->checkRequestMethod( $instance->allowRequestMethod ) ) {
            return $this->api->getError( 408 );
        }

        return $instance->response();
    }

    /**
     * 检查 请求方式是否允许
     *
     * @param array $allowRequestMethod
     *
     * @return bool
     */
    private function checkRequestMethod( $allowRequestMethod = [] ) {
        $requestMethod = strtolower( request()->method() );
        if ( empty( $allowRequestMethod ) ) {
            return FALSE;
        }

        return isset( $allowRequestMethod[ $requestMethod ] );
    }
}
