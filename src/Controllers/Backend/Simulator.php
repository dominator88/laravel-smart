<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 16:41
 */

namespace Smart\Controllers\Backend;



use Smart\Service\MerUserService;
use Smart\Service\SimulatorService;
use Smart\Service\SysMerchantService;
use cebe\markdown\MarkdownExtra;
use Illuminate\Http\Request;


class Simulator extends Backend {

    public $deviceOsVersion = '10.0.0';
    public $apiVersion      = 'v1';

    /**
     * Simulator constructor.
     */
    public function __construct(Request $request) {
        parent::__construct($request);
        $this->_initClassName( $this->controller );
        $this->service = SimulatorService::instance();
    }

    //页面入口
    public function index() {
        $this->_init( '接口模拟器' );

        //uri
        $this->_addParam( 'uri' , [
            'readApi'    => full_uri( 'backend/simulator/read_api' ) ,
            'readParams' => full_uri( 'backend/simulator/read_params' ) ,
            'api'        => $this->baseUri . "api/{$this->apiVersion}/" ,
            'readme'     => full_uri( 'backend/simulator/read_me' ) ,
        ] );

        $MerUser     = MerUserService::instance();
        $SysMerchant = SysMerchantService::instance();
        //其他参数
        $this->_addParam( [
            'deviceOsVersion' => $this->deviceOsVersion ,
            'apiVersion'      => $this->apiVersion ,
            'secret'          => config( 'api.secret' ) ,
            'testToken'       => $MerUser->getForTest() ,
            'testMer'         => $SysMerchant->getForTest() ,
            'defaultValue'    => [
                'token' => '' , //取一个token
                'merId' => 1
            ]
        ] );

        //需要引入的 css 和 js
        $this->_addJsLib( 'static/plugins/jquery-md5/jQuery.md5.js' );

        return $this->_displayWithLayout('simulator.index');
    }

    //读取结果
    function read_api() {
        $Simulator = SimulatorService::instance();

        $ret = $Simulator->readApi( $this->apiVersion );

        return json( $ret );
    }

    function read_params(Request $request) {
        $directory = $request->input( 'directory' );
        $action    = $request->input( 'action' );
        $action    = ucfirst( $action );
        $method    = $request->input( 'method' , '' );

        $service = "App\\Http\\Controllers\\Api\\Service\\{$this->apiVersion}\\{$directory}\\{$action}Service";

        $instance = $service::instance();

        if ( empty( $method ) ) {
            $key    = array_keys( $instance->allowRequestMethod );
            $method = $key[0];
        }

        $data['method']             = $method;
        $data['allowRequestMethod'] = $instance->allowRequestMethod;
        $data['defaultParams']      = $instance->defaultParams[ $method ];
        $data['defaultResponse']    = $this->_fixDefaultResponse( $instance->defaultResponse[ $method ] );

        return view('simulator.params')->with($data);

    }

    /**
     * 优化前台显示 default response
     *
     * @param $defaultResponse
     *
     * @return string
     */
    private function _fixDefaultResponse( $defaultResponse ) {
        foreach ( $defaultResponse as $key => &$item ) {
            if ( is_array( $item ) ) {
                if ( isset( $item[0] ) ) {
                    $item = $item[0];
                } else {
                    foreach ( $item as $k => &$i ) {
                        if ( is_array( $i ) ) {
                            if ( isset( $i[0] ) ) {
                                $i = $i[0];
                            }
                        }
                    }
                }
            }
        }

        return json_encode( $defaultResponse , JSON_UNESCAPED_UNICODE );
    }

    //文档
    function read_me() {
        $this->_init( '文档' );
        $parser = new MarkdownExtra();
        $readme = $parser->parse( file_get_contents( ROOT_PATH . './README.md' ) );

        $this->_addData( 'readme' , $readme );

        $this->_addParam( 'uri' , [
            'menu' => '/backend/simulator/index' ,
        ] );

        $this->_addJsLib( 'static/js/backend/SimulatorReadme.js' );
        $this->data['initPageJs'] = FALSE;
        $this->data['jsCode'][]   = 'SimulatorReadme.init()';


        return $this->_displayWithLayout( 'readme' );
    }

}