<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 21:13
 */
namespace Smart\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Smart\Models\SysApiLog;

class ApiService {

    const PARAM_REQUIRED = 'required';
    const PARAM_DIGIT    = 'digit';
    const PARAM_POSITIVE = 'positive';

    use \Smart\Traits\Service\Instance;

    public $debug           = TRUE;
    public $params          = [];
    public $defaultParams   = [];
    public $defaultResponse = [];
    public $userId          = '';
    public $behalf          = '';
    public $merId           = '';
    public $error           = '';
    public $errCode         = 500;

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


    public function params($params = []){
        $this->params = $params;
    }

    public function getError( $code ) {
        return api_result( $this->code[ $code ] , $code );
    }

    /**
     * 数据签名
     *
     * @param $inputArr
     *
     * @return string
     */
    public function signature( $inputArr ) {
        ksort( $inputArr );

        $new_arr = [];
        foreach ( $inputArr as $key => $val ) {
            $val = htmlspecialchars_decode( $val );
            if ( is_array( $val ) ) {
                $val = json_encode( $val , JSON_UNESCAPED_UNICODE );
            }
            $new_arr[] = "$key=$val";
            //  echo "$key=$val <br/>";
        }

        $signature = implode( '&' , $new_arr ) . '&secret=' . config( 'backend.secret' );

        $this->log( '签名:' , $inputArr );

        return md5( $signature );
    }

    /**
     * 校验签名
     *
     * @param $metaData
     * @param $signature
     *
     * @return bool
     */
    public function validSignature( $metaData , $signature ) {

        if ( empty( $signature ) ) {
            return FALSE;
        }

        if ( isset( $metaData['file_data'] ) ) {
            unset( $metaData['file_data'] );
        }

        $newSignature = $this->signature( $metaData );
        $this->log( 'server signature' , $newSignature );

        return $signature == $newSignature;
    }

    /**
     * 验证时间戳
     *
     * @param $timestamp
     *
     * @return bool
     */
    public function validTimestamp( $timestamp ) {
        $this->log( 'server time' , time() );
        $this->log( 'client time' , $timestamp );
        $this->log( 'time_diff' , abs( time() - $timestamp ) );
        if ( empty( $timestamp ) ) {
            return FALSE;
        }

        return abs( time() - $timestamp ) < config( 'backend.timeGap' );
    }

    /**
     * 验证用户
     *
     * @return mixed
     */
/*     public function validToken( ) {

    //    $this->token = resolve( TokenService::class );
        $this->userId = '';
        $this->error  = 500;

        if ( ! isset( $this->params['api_token'] ) || empty( $this->params['api_token'] ) ) {
            //参数错误
            $this->error = '请填写token';

            return FALSE;
        } else {

         //   $MemberData = $this->token->getByToken($this->params['token']);
            $MemberData = Auth::guard('api')->user();

            if ( empty( $MemberData ) ) {
                //数据未找到
                $this->error   = '认证失败';
                $this->errCode = 403;

                return FALSE;
            }

            $this->userId = $MemberData->id;

            return TRUE;
        }
    } */

    /**
     * 验证单个参数
     *
     * @param $paramName
     * @param string $rule
     *
     * @return bool
     */
//	public function validParam( $paramName, $rule = PARAM_REQUIRED ) {
//		$this->error   = '';
//		$this->errCode = 500;
//		switch ( $rule ) {
//			case  PARAM_REQUIRED :
//				if ( empty( trim( $this->requestParams[ $paramName ] ) ) ) {
//					$this->error = "$paramName 不能为空";
//
//					return FALSE;
//				}
//		}
//
//		return TRUE;
//	}

    /**
     * 验证全部参数
     *
     * @return bool
     */
    public function validParams() {
        $method = strtolower( request()->method() );
        foreach ( $this->defaultParams[ $method ] as $key => $defined ) {
            //如果是非必填参数 则赋值为 默认值,以避免程序错误
            if ( ! isset( $defined[2] ) ) {
                //检查是否填写必填参数
                if ( ! isset( $this->params[ $key ] ) ) {
                    if ( isset( $defined[1] ) ) {
                        $this->params[ $key ] = $defined[1];
                    } else {
                        $this->error = "请填写 $key ";

                        return FALSE;
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

                    return FALSE;
                }
            }
            $value = trim( $this->params[ $key ] );
            $rule  = $defined[2];

            if ( is_array( $rule ) ) {
                if ( ! isset( $rule[ $value ] ) ) {
                    $this->error = "请填写正确的 $key ";

                    return FALSE;
                }
            } else {
                switch ( $rule ) {
                    case self::PARAM_REQUIRED :
                        //判断必填
                        if ( $value === '' && empty( $value ) ) {
                            $this->error = "请填写 $key ";

                            return FALSE;
                        }
                        if ( $key == 'merId' ) {
                            $this->merId = $value;
                        }
                        break;
                    case self::PARAM_DIGIT:
                        //判断是数字
                        if ( ! is_numeric( $value ) ) {
                            $this->error = " $key 不是是数字";

                            return FALSE;
                        }
                        break;
                    case self::PARAM_POSITIVE :
                        //判断是否是正数
                        if ( ! is_numeric( $value ) || $value <= 0 ) {
                            $this->error = " $key 必须大于0";

                            return FALSE;
                        }
                        break;
                }
            }
        }

        return TRUE;
    }

    /**
     * 格式化数据
     *
     * @param $data
     * @param $defaultResponse
     *
     * @return array
     */
    public function formatData( $data , $defaultResponse = [] ) {
        if ( empty( $data ) ) {
            return [];
        }

        if ( empty( $defaultResponse ) ) {
            $method          = strtolower( request()->method() );
            $defaultResponse = $this->defaultResponse[ $method ];
        }

        if ( empty( $defaultResponse ) ) {
            return $data;
        }

        $newData = [];
        if ( isset( $data[0] ) ) {
            foreach ( $data as $item ) {
                $newData[] = $this->formatDataForRow( $defaultResponse , $item );
            }
        } else {
            $newData = $this->formatDataForRow( $defaultResponse , $data );
        }

        return $newData;
    }

    /**
     * 格式化一行数据
     *
     * @param $defaultResponse
     * @param $data
     *
     * @return array
     */
    private function formatDataForRow( $defaultResponse , $data ) {
        $newData = [];
        foreach ( $defaultResponse as $key => $defined ) {
            if ( isset( $data[ $key ] ) && is_array( $data[ $key ] ) ) {
                foreach ( $data[ $key ] as $k => $row ) {
                    $newData[ $key ][ $k ] = $this->formatDataForRow( $defined , $row );
                }
            } else {
                if ( is_array( $defined ) && isset( $defined[1] ) && method_exists( $this , $defined[1] ) ) {
                    $formatter       = $defined[1];
                    $value           = isset( $data[ $key ] ) ? $data[ $key ] : '';
                    $newData[ $key ] = $this->$formatter( $value , $data );
                } else {
                    $newData[ $key ] = isset( $data[ $key ] ) ? $data[ $key ] : '';
                }
            }
        }

        return $newData;
    }

    //格式化 图标
    public function formatIcon( $value , $row = [] ) {
        if ( filter_var( $value , FILTER_VALIDATE_URL , FILTER_FLAG_SCHEME_REQUIRED ) ) {
            return $value;
        }

        return full_img_uri( $value );
    }

    //格式化 手机号
    public function formatPhone( $value , $row = [] ) {
        return substr_replace( $value , '****' , 3 , 4 );
    }

    /**
     * 记录日志
     *
     * @param $key
     * @param $value
     */
    public function log( $key , $value = '' ) {
        if ( ! $this->debug ) {
            return;
        }

        $filename = 'logs/api_log_' . date( 'Y_m_d' ) . '.txt';
        
        if(Storage::disk('local')->exists($filename)){
            Storage::disk('local')->put($filename,'');
        }
        

        $value = is_array( $value ) ? print_r( $value , TRUE ) : $value;

        $text = "----------" . date( 'Y-m-d H:i:s' ) . " 开始----------\r\n";
        $text .= " $key = $value  \r\n";
        Storage::disk('local')->append($filename, $text);
        
    }

    public function logStat( $param ) {
        $data = [
            'device'            => $param['device'] ,
            'device_os_version' => $param['deviceOsVersion'] ,
            'app_version'       => $param['appVersion'] ,
            'api_version'       => $param['apiVersion'] ,
            'uri'               => request()->url( TRUE ) ,
            'ip'                => request()->ip( 0 , TRUE )
        ];
        SysApiLog::create($data);
    }


}