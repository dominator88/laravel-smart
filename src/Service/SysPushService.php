<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/22
 * Time: 17:24
 */
 namespace Smart\Service;


use Smart\Models\SysPush;


use JPush;



class SysPushService extends BaseService {

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable;

    public $catalog = [
        'alert' => '通知',
        'order' => '订单',
        'event' => '活动'
    ];

    public $platform = [
        'all'     => '全部',
        'ios'     => 'iOS',
        'android' => '安卓',
    ];

    //状态
    public $status = [
        0 => '未发送',
        1 => '已发送',
    ];

    private $jpush;
    //类实例
    private static $instance;

    //生成类单例
    public static function instance() {
        if ( self::$instance == NULL ) {
            self::$instance        = new SysPushService();
            self::$instance->setModel(new SysPush());
            self::$instance->initJPush();
        }

        return self::$instance;
    }

    /**
     * 初始化 jpush 接口
     *
     * @param string $appKey
     * @param string $secret
     */
    public function initJPush( $appKey = '', $secret = '' ) {
        $appKey = empty( $appKey ) ? config( 'backend.JPush.appKey' ) : '';
        $secret = empty( $secret ) ? config( 'backend.JPush.secret' ) : '';

     //   $this->jpush = new JPush( $appKey, $secret );
    }

    //取默认值
    public function getDefaultRow() {
        return [
            'id'              => '',
            'mer_id'          => '',
            'catalog'         => 'alert',
            'platform'        => 'all',
            'registration_id' => '',
            'alias'           => '',
            'tags'            => '',
            'title'           => '',
            'content'         => '',
            'param'           => '',
            'status'          => '0',
            'sent_at'         => '',
            'created_at'      => date( 'Y-m-d H:i:s' ),
        ];
    }

    /**
     * 根据条件查询
     *
     * @param $param
     *
     * @return array|number
     */
    function getByCond( $param ) {
        $default = [
            'field'    => ['*'],
            'keyword'  => '',
            'status'   => '',
            'page'     => 1,
            'pageSize' => 10,
            'sort'     => 'id',
            'order'    => 'DESC',
            'count'    => FALSE,
            'getAll'   => FALSE
        ];

        $param = extend( $default, $param );

        $model = $this->getModel()->keyword($param['keyword'])->status($param['status']);


        if ( $param['count'] ) {
            return $model->count();
        }



        $data = $model->getAll($param)->orderBy($param['sort'] , $param['order'])->get()->toArray($param['field']);

        return $data ? $data : [];
    }

    /**
     * 绑定用户ID
     *
     * @param $registrationId
     * @param $userId
     *
     * @return array
     */
    public function bindByUserId( $registrationId, $userId ) {
        try {
            $result = $this->jpush->device()->updateDevice( $registrationId, (string) $userId );

            return ajax_arr( '成功', 0, json_decode( json_encode( $result ), TRUE ) );
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage(), 500 );
        }
    }

    public function sendById( $id ) {
        $data = $this->getById( $id );
        if ( empty( $data ) ) {
            return ajax_arr( '推送消息未找到', 500 );
        }

        if ( ! empty( $data['alias'] ) ) {
            $data['alias'] = explode( ',', $data['alias'] );
        }

        if ( ! empty( $data['tags'] ) ) {
            $data['tags'] = explode( ',', $data['tags'] );
        }

        if ( ! empty( $data['registrationId'] ) ) {
            $data['registrationId'] = explode( ',', $data['registrationId'] );
        }

        if ( ! empty( $data['extras'] ) ) {
            $data['extras'] = json_decode( $data['extras'], TRUE );
        }

        $result = $this->sendByCond( $data );
        if ( $result['code'] != 0 ) {
            return $result;
        }

        //更新数据
        try {
            $this->getModel()->where( 'id', $id )->update( [
                'status'  => 1,
                'sent_at' => date( 'Y-m-d H:i:s' )
            ] );

            return ajax_arr( '推送成功', 0 );
        } catch ( \Exception $e ) {
            return ajax_arr( '发送失败 ,' . $e->getMessage(), 500 );
        }
    }

    /**
     * 根据条件发送
     *
     * @param $params
     *
     * @return array
     */
    public function sendByCond( $params ) {
        $default = [
            'alias'          => [],
            'tags'           => [],
            'registrationId' => [],
            'title'          => '',
            'alert'          => '',
            'platform'       => 'all',
            'extras'         => [],
        ];

        $params = extend( $default, $params );

        try {

            $push = $this->jpush->push();

            $push->setPlatform( $params['platform'] );

            if ( ! empty( $params['alias'] ) ) {
                $push->addAlias( $params['alias'] );
            }

            if ( ! empty( $params['tags'] ) ) {
                $push->addTag( $params['tags'] );
            }

            if ( ! empty( $params['registrationId'] ) ) {
                $push->addRegistrationId( $params['registrationId'] );
            }


            if ( $params['platform'] == 'all' ) {
                //发送到所有平台
                if ( empty( $params['extras'] ) ) {
                    $push->setNotificationAlert( $params['alert'] );
                } else {
                    $push->addIosNotification( $params['alert'], 'sound', '+1', NULL, NULL, $params['extras'] )
                        ->addAndroidNotification( $params['alert'], $params['title'], NULL, $params['extras'] );
                }
            } else if ( $params['platform'] == 'ios' ) {
                //仅发 ios
                if ( empty( $params['extras'] ) ) {
                    $push->addIosNotification( $params['alert'] );
                } else {
                    $push->addIosNotification( $params['alert'], 'sound', '+1' );
                }
            } else if ( $params['platform'] == 'android' ) {
                //仅发 android
                if ( empty( $params['extras'] ) ) {
                    $push->androidNotification( $params['alert'], $params['title'] );
                } else {
                    $push->androidNotification( $params['alert'], $params['title'], NULL, $params['extras'] );
                }
            }

            $response = $push->send();

            return ajax_arr( '发送成功', 0, $response );
        } catch ( \APIConnectionException $e ) {
            // try something here
            return ajax_arr( $e->getMessage(), 500 );
        } catch ( \APIRequestException $e ) {
            // try something here
            return ajax_arr( $e->getMessage(), 500 );
        }
    }

    public function insert( $data, $sendImmediately = TRUE ) {


    }

}