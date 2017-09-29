<?php namespace Smart\Controllers\Api\Service\v1\system;
/**
 * 区域
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 21:28
 */


use Smart\Controllers\Api\Service\v1\ApiService;
use App\Service\SysAreaService;


class AreaService extends ApiService {

    //允许的请求方式
    public $allowRequestMethod = [
        'get' => 'GET - 取区域' ,
    ];

    /**
     * 传参 如:
     * 'title' => ['标题' , '默认值' , '验证方式'] //验证方式可选
     * 'status' => ['状态' , 1 , ["0" => '禁用' , 1 => '启用'] ]
     */
    public $defaultParams = [
        'get' => [
            'pid' => [ '上级ID' , '0' , PARAM_REQUIRED ] ,
        ] ,
    ];

    /**
     * 返回结果示例 如:
     *
     * 'user_id'     => '用户ID',
     * 'icon' => ['头像' , 'formatIcon'] , //第二个值为格式化方法
     */
    public $defaultResponse = [
        'get' => [
            "id"   => "区域ID" ,
            "pid"  => "上级ID" ,
            "text" => "区域名称" ,
        ] ,
    ];

    private static $instance;

    public static function instance( $params = [] ) {
        if ( self::$instance == NULL ) {
            self::$instance         = new AreaService();
            self::$instance->params = $params;
        }

        return self::$instance;
    }

    /**
     * 接口响应方法
     *
     * @return array
     */
    public function response() {


        if ( ! $this->validParams() ) {
            return api_result( $this->error , 500 );
        }

        //处理业务
        switch ( request()->method() ) {
            case 'GET' :
                $data = $this->get();
                $data = $this->formatData( $data );

                return api_result( '查询成功' , 0 , [ 'rows' => $data ] );
            default :
                return api_result( '未知请求类型' , 500 );
        }
    }

    /**
     * get 的响应方法
     *
     * @return array|number
     */
    public function get() {
        $cacheName = config( 'custom.areaCachePrefix' ) . $this->params['pid'];
        $data      = cache( $cacheName );
        if ( empty( $data ) ) {
            $SysArea = SysAreaService::instance();
            $data    = $SysArea->getByCond( [
                'pid'    => $this->params['pid'] ,
                'status' => 1 ,
                'getAll' => TRUE
            ] );
            cache( $cacheName , $data , 86400 );
        }

        return $data;
    }
}
