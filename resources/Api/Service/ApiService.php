<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 21:13
 */
namespace App\Http\Controllers\Api\Service\v1;

use Smart\Interfaces\TokenService;
use Smart\Service\AuthUcService;

use Smart\Service\MerUserDeviceService;
use Illuminate\Support\Facades\DB;

define( 'PARAM_REQUIRED' , 'required' );
define( 'PARAM_DIGIT' , 'digit' );
define( 'PARAM_POSITIVE' , 'positive' );

class ApiService {

    public $debug           = true;
    public $params          = [];
    public $defaultparams   = [];
    public $defaultresponse = [];
    public $userid          = '';
    public $behalf          = '';
    public $merid           = '';
    public $error           = '';
    public $errcode         = 500;

    public $token = '';

    //出错代码表
    public $code = [
        0   => 'success' ,

        //客户端问题
        400 => 'param error' ,
        401 => 'not login' ,
        403 => 'please login' ,
        404 => 'operator not found' ,
        405 => 'error timestamp' ,
        406 => 'error signature' ,
        407 => 'unknown error' ,
        408 => 'no allow request method' ,

        //服务端问题
        500 => 'runtime error' ,
        503 => 'server not found' ,
        504 => 'data not found' ,
        505 => 'data exist'
    ];

    private static $instance;



    public static function instance() {
        if ( self::$instance == null ) {
            self::$instance = new ApiService();
        }
        return self::$instance;
    }



    public function geterror( $code ) {
        return api_result( $this->code[ $code ] , $code );
    }

    /**
     * 数据签名
     *
     * @param $inputarr
     *
     * @return string
     */
    public function signature( $inputarr ) {
        ksort( $inputarr );

        $new_arr = [];
        foreach ( $inputarr as $key => $val ) {
            $val = htmlspecialchars_decode( $val );
            if ( is_array( $val ) ) {
                $val = json_encode( $val , JSON_UNESCAPED_UNICODE );
            }
            $new_arr[] = "$key=$val";
            //  echo "$key=$val <br/>";
        }

        $signature = implode( '&' , $new_arr ) . '&secret=' . config( 'backend.secret' );

        $this->log( '签名:' , $inputarr );

        return md5( $signature );
    }

    /**
     * 校验签名
     *
     * @param $metadata
     * @param $signature
     *
     * @return bool
     */
    public function validsignature( $metadata , $signature ) {

        if ( empty( $signature ) ) {
            return false;
        }

        if ( isset( $metadata['file_data'] ) ) {
            unset( $metadata['file_data'] );
        }

        $newsignature = $this->signature( $metadata );
        $this->log( 'server signature' , $newsignature );

        return $signature == $newsignature;
    }

    /**
     * 验证时间戳
     *
     * @param $timestamp
     *
     * @return bool
     */
    public function validtimestamp( $timestamp ) {
        $this->log( 'server time' , time() );
        $this->log( 'client time' , $timestamp );
        $this->log( 'time_diff' , abs( time() - $timestamp ) );
        if ( empty( $timestamp ) ) {
            return false;
        }

        return abs( time() - $timestamp ) < config( 'backend.timegap' );
    }

    /**
     * 验证用户
     *
     * @return mixed
     */
    public function validtoken() {
        $this->token = resolve( tokenservice::class );
        $this->userid = '';
        $this->error  = 500;

        if ( ! isset( $this->params['token'] ) || empty( $this->params['token'] ) ) {
            //参数错误
            $this->error = '请填写token';

            return false;
        } else {

            $memberdata = $this->token->getbytoken($this->params['token']);

            //   $this->log('用户数据',implode(',',$memberdata));
            /* $meruserdevice = meruserdeviceservice::instance();
             $devicedata    = $meruserdevice->getbytoken( $this->params['token'] );*/
            //$devicedata    = $meruserdevice->getbytoken( $this->params['token'], $this->params['device'] );

            if ( empty( $memberdata ) ) {
                //数据未找到
                $this->error   = '认证失败';
                $this->errcode = 403;

                return false;
            }

            $this->userid = $memberdata->id;

            return true;
        }
    }

    /**
     * 验证单个参数
     *
     * @param $paramname
     * @param string $rule
     *
     * @return bool
     */
//	public function validparam( $paramname, $rule = param_required ) {
//		$this->error   = '';
//		$this->errcode = 500;
//		switch ( $rule ) {
//			case  param_required :
//				if ( empty( trim( $this->requestparams[ $paramname ] ) ) ) {
//					$this->error = "$paramname 不能为空";
//
//					return false;
//				}
//		}
//
//		return true;
//	}

    /**
     * 验证全部参数
     *
     * @return bool
     */
    public function validparams() {
        $method = strtolower( request()->method() );
        foreach ( $this->defaultparams[ $method ] as $key => $defined ) {
            //如果是非必填参数 则赋值为 默认值,以避免程序错误
            if ( ! isset( $defined[2] ) ) {
                //检查是否填写必填参数
                if ( ! isset( $this->params[ $key ] ) ) {
                    if ( isset( $defined[1] ) ) {
                        $this->params[ $key ] = $defined[1];
                    } else {
                        $this->error = "请填写 $key ";

                        return false;
                    }
                }
                continue;
            }

            //如果未定义验证规则 继续下一个变量
            if ( $defined[2] == 'file' ) {
                continue;
            }

            //检查是否填写必填参数
            if ( ! isset( $this->params[ $key ] ) ) {
                if ( isset( $defined[1] ) ) {
                    $this->params[ $key ] = $defined[1];
                } else {
                    $this->error = "请填写 $key ";

                    return false;
                }
            }
            $value = trim( $this->params[ $key ] );
            $rule  = $defined[2];

            if ( is_array( $rule ) ) {
                if ( ! isset( $rule[ $value ] ) ) {
                    $this->error = "请填写正确的 $key ";

                    return false;
                }
            } else {
                switch ( $rule ) {
                    case PARAM_REQUIRED :
                        //判断必填
                        if ( $value === '' && empty( $value ) ) {
                            $this->error = "请填写 $key ";

                            return false;
                        }
                        if ( $key == 'merid' ) {
                            $this->merid = $value;
                        }
                        break;
                    case PARAM_DIGIT:
                        //判断是数字
                        if ( ! is_numeric( $value ) ) {
                            $this->error = " $key 不是是数字";

                            return false;
                        }
                        break;
                    case PARAM_POSITIVE :
                        //判断是否是正数
                        if ( ! is_numeric( $value ) || $value <= 0 ) {
                            $this->error = " $key 必须大于0";

                            return false;
                        }
                        break;
                }
            }
        }

        return true;
    }

    /**
     * 格式化数据
     *
     * @param $data
     * @param $defaultresponse
     *
     * @return array
     */
    public function formatdata( $data , $defaultresponse = [] ) {
        if ( empty( $data ) ) {
            return [];
        }

        if ( empty( $defaultresponse ) ) {
            $method          = strtolower( request()->method() );
            $defaultresponse = $this->defaultresponse[ $method ];
        }

        if ( empty( $defaultresponse ) ) {
            return $data;
        }

        $newdata = [];
        if ( isset( $data[0] ) ) {
            foreach ( $data as $item ) {
                $newdata[] = $this->formatdataforrow( $defaultresponse , $item );
            }
        } else {
            $newdata = $this->formatdataforrow( $defaultresponse , $data );
        }

        return $newdata;
    }

    /**
     * 格式化一行数据
     *
     * @param $defaultresponse
     * @param $data
     *
     * @return array
     */
    private function formatdataforrow( $defaultresponse , $data ) {
        $newdata = [];
        foreach ( $defaultresponse as $key => $defined ) {
            if ( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ) {
                foreach ( $data[ $key ] as $k => $row ) {
                    $newdata[ $key ][ $k ] = $this->formatdataforrow( $defined , $row );
                }
            } else {
                if ( is_array( $defined ) && isset( $defined[1] ) && method_exists( $this , $defined[1] ) ) {
                    $formatter       = $defined[1];
                    $value           = isset( $data[ $key ] ) ? $data[ $key ] : '';
                    $newdata[ $key ] = $this->$formatter( $value , $data );
                } else {
                    $newdata[ $key ] = isset( $data[ $key ] ) ? $data[ $key ] : '';
                }
            }
        }

        return $newdata;
    }

    //格式化 图标
    public function formaticon( $value , $row = [] ) {
        if ( filter_var( $value , FILTER_VALIDATE_URL  , FILTER_FLAG_SCHEME_REQUIRED ) ) {
            return $value;
        }

        return full_img_uri( $value );
    }

    //格式化 手机号
    public function formatphone( $value , $row = [] ) {
        return substr_replace( $value , '****' , 3 , 4 );
    }

    /**
     * 记录日志
     *
     * @param $key
     * @param $value
     */
    public function log( $key , $value = '' ) {$this->debug = true;
        if ( ! $this->debug ) {
            return;
        }

        $filename = './logs/api_log_' . date( 'y_m_d' ) . '.txt';

        if ( ! file_exists( $filename ) ) {
            if( ! file_exists($filename)){ mkdir( dirname($filename));}
            file_put_contents( $filename , '' );
            chmod( $filename , 0777 );
        }

        $value = is_array( $value ) ? print_r( $value , true ) : $value;

        $text = "----------" . date( 'y-m-d h:i:s' ) . " 开始----------\r\n";
        $text .= " $key = $value  \r\n";
        // $text .= "----------结束----------\r\n" ;

        file_put_contents( $filename , $text , FILE_APPEND );
    }

    public function logstat( $param ) {
        $data = [
            'device'            => $param['device'] ,
            'device_os_version' => $param['deviceosversion'] ,
            'app_version'       => $param['appversion'] ,
            'api_version'       => $param['apiversion'] ,
            'uri'               => request()->url( true ) ,
            'ip'                => request()->ip( 0 , true )
        ];

        db::table( 'sys_api_log' )->insert( $data );
    }

    protected  function statistics($data){
        $data_r['num'] = array_sum(array_column($data,'num'));
        $data_r['num1'] = array_sum(array_column($data,'num1'));
        $data_r['amount'] = array_sum(array_column($data,'pay_amount'));
        $data_r['payment'] = array_sum(array_column($data,'goods_amount'));
        return $data_r;
    }

}