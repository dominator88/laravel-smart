<?php

namespace Smart\Middleware;

use Closure;
use App\Api\Service\v1\ApiService;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    protected $api = NULL;



    public function __construct()
    {
        $this->api = ApiService::instance();
        $this->api->debug = false;
    }

    public function handle($request, Closure $next)
    {
        $api = $this->api;

        $timestamp = $request->header( 'timestamp' );
        $signature = $request->header( 'signature' );

        if ( ! $api->validTimestamp( $timestamp ) ) {
             json( $api->getError( 405 ) )->send();
        }
        $params = $request->all();
        $api->log( 'params' , $params );

        //取时间戳
        $params['timestamp'] = $timestamp;

        //检查签名
        if ( ! $api->validSignature( $params , $signature ) ) {
           json( $api->getError( 406 ) )->send() ;
        }



        // 参数错误
        if ( ! is_array( $params ) || empty( $params ) ) {
            json( $api->getError( 400 ) )->send() ;
        }

        return $next($request);
    }

    public function terminate($request , $response){
        $header = [
            'timestamp'       => $request->header( 'timestamp' ) ,
            'signature'       => $request->header( 'signature' ) ,
            'device'          => $request->header( 'device' ) ,
            'deviceOsVersion' => $request->header( 'device-os-version' ) ,
            'appVersion'      => $request->header( 'app-version' ) ,
            'apiVersion'      => $request->input('version') ,
        ];
        $this->api->logStat( $header );
        $this->api->log( 'headerData' , $header );


    }
}
