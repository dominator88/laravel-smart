<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/25
 * Time: 10:23
 */

namespace Smart\Service;


use Smart\Models\SysSms;
use Toplan\PhpSms\Sms;

class SysSmsService extends BaseService {

    public $error = '';

    const CaptchaVerifyPeriod = 15; //验证码 验证有效期 单位:分钟

    //引入 GridTable trait
    use \Smart\Traits\Service\GridTable,\Smart\Traits\Service\Instance;

    protected $model_class = SysSms::class;

    public $type = [
        'captcha' => '验证码'
    ];

    //状态
    public $status = [
        -1 => '未发送',
        0   => '未验证',
        1   => '已验证',
    ];

    

    //取默认值
    function getDefaultRow() {
        return [
            'id'          => '',
            'type'        => 'captcha',
            'phone'       => '',
            'content'     => '',
            'temp_id'     => '',
            'create_time' => date( 'Y-m-d H:i:s' ),
            'valid_time'  => '',
            'send_time'   => '',
            'message_id'  => '',
            'status'      => '0',
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

        $data = $model->getAll($param)->orderBy($param['sort'] , $param['order'])->get($param['field'])->toArray();

        return $data ? $data : [];
    }

    /**
     * 创建验证码
     *
     * @param $phone
     * @param bool $sendImmediately
     *
     * @return array
     */
    public function createByCaptcha( $phone, $sendImmediately = FALSE ) {
        $oldData = $this->getByPhoneInOneMin( $phone );

        if ( ! empty( $oldData ) ) {
            return ajax_arr( '请1分钟后再试试', 500 );
        }

        $data = [
            'phone'   => $phone,
            'content' => $this->makeCaptcha(),
            'temp_id' => config( 'custom.sms' )['captchaTempId']
        ];

        try {
            $id = $this->getModel()->insertGetId( $data );

            if ( $sendImmediately ) {
                if ( ! $this->sendCaptcha( $phone, $data['content'], $data['temp_id'] ) ) {
                    throw new \Exception( $this->error );
                }

                $this->getModel()->where( 'id', $id )->update( [
                    'sent_at' => date( 'Y-m-d H:i:s' ),
                    'status'  => 0
                ] );
            }

            return ajax_arr( '创建成功', 0 );
        } catch ( \Exception $e ) {
            return ajax_arr( $e->getMessage(), 500 );
        }
    }

    /**
     * 生成验证码
     *
     * @return int
     */
    private function makeCaptcha() {
        return mt_rand( 100000, 999999 );
    }


    /**
     * 发送验证码
     *
     * @param $phone
     * @param $captcha
     * @param $tempId
     *
     * @return bool
     */
    public function sendCaptcha( $phone, $captcha, $tempId ) {
        $this->error  = '';

        $templates = [
         //   'YunTongXun' => 'your_temp_id',
          //  'SubMail'    => 'your_temp_id'
            'MySms' => $tempId,
        ];
    // 模版数据
        $tempData = [
            'code' => $captcha,
            'minutes' => '5'
        ];

        $result  = Sms::make()->to( $phone)->template($templates)->data($tempData)->send();
        if ( $result == NULL ) {
            $this->error = '返回错误';

            return FALSE;
        }
        if ( $result->statusCode != 0 ) {
            $this->error = (string) $result->statusMsg;

            return FALSE;
        }

        return TRUE;
    }

    /**
     * 取1分钟内发送的验证码
     *
     * @param $phone
     *
     * @return mixed
     */
    public function getByPhoneInOneMin( $phone ) {
        $data = $this->getModel()
            ->where( 'phone', $phone )
            ->whereTime( 'created_at', '>', time() - 60 )
            ->find();

        return $data;
    }

    /**
     * 校验验证码
     *
     * @param $phone
     * @param $captcha
     *
     * @return bool
     */
    public function validCaptcha( $phone, $captcha ) {

        //获取验证码
        $data = $this->getModel()
            ->where( 'phone', $phone )
            ->where( 'status', 0 )
            ->whereTime( 'sent_at', '>', time() - self::CaptchaVerifyPeriod * 60 )
            ->order('sent_at DESC')
            ->find();

//		echo  $this->model->getLastSql();
        if ( ! $data ) {
            $this->error = '验证码未找到';

            return FALSE;
        }

        if ( $data['content'] != $captcha ) {
            $this->error = '验证码不正确';

            return FALSE;
        }

        $this->getModel()->where('id' , $data['id'])->update([
            'verified_at' => date( 'Y-m-d H:i:s' ),
            'status'      => 1,
        ]);
        //验证成功

        return TRUE;
    }

}