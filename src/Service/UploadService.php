<?php
/**
 * 文件图片上传 Service
 *
 * @author Zix
 * @version 2.0 , 2016-05-06
 */

namespace Smart\Service;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadService {

    private $error;
    private $path = [ 'icon', 'merchant', 'tmp', 'app' ];
    private $type = [ 'img', 'file', 'both' ];
    private $inputName = 'imgFile';
    private $defaultParam = [
        'type'        => 'img',  //上传类型 img , file , all
        'path'        => 'icon', //上传路径 icon merchant tmp app file
        'maxSize'     => 3145728, //上传尺寸
        'merId'       => '', //商户ID
        'isKE'        => 0, //是否kindeditor
        'crop'        => [], //是否裁剪 数组 [ x , y , w , h ],
        'ratio'       => 1,  //缩放比
        'width'       => 0,  //宽度
        'height'      => 0,  //高度
        'thumb'       => [], //缩略图 [50 , 100 , 300] 或 [ 50|50 , 100|100 , 300|300 ]
        'saveAsAlbum' => FALSE, //是否保存到相册
        'albumTag'    => '', //相册目录
    ];

    public $param = []; //上传的配置文件
    private $fileInfo = NULL; //上传成功后的 file info
    private $result = []; //返回结果
    private $uploadFtpList = []; //需要ftp上传的文件
    private $relativePath = '';


    private static $instance;

    public static function instance( $inputName = 'imgFile') {
        if ( self::$instance == NULL ) {
            self::$instance = new UploadService();
            self::$instance->inputName = $inputName ;
        }

        return self::$instance;
    }

    public function setError( $error ) {
        $this->error = $error;
    }

    public function getError() {
        return $this->error;
    }

    public function setInputName( $inputName ) {
        $this->inputName = $inputName;
    }


    private function initUpload( $param ) {
        $this->uploadFtpList = [];
        $this->fileInfo      = NULL;
        $this->result        = [];
        $this->relativePath  = '';
        $this->param         = extend( $this->defaultParam, $param );
    }

    /**
     * 上传
     *
     * 图片 先裁剪 再按宽高缩放
     *
     * @param $param
     *
     * @return array
     */
    public function doUpload( $param = [] ) {

        //初始化各种变量
        $this->initUpload( $param );

        //检查上传类型
        if ( ! in_array( $this->param['type'], $this->type ) ) {
            $this->setError( '未知的上传类型' );

            return $this->returnError();
        }

        //检查存储路径
        if ( ! in_array( $this->param['path'], $this->path ) ) {
            $this->setError( '未知的上传路径' );

            return $this->returnError();
        }

        //检查尺寸
        $file = request()->file( $this->inputName );
        if ( $file->getSize() > $this->param['maxSize'] ) {
            $size = file_size( $this->param['maxSize'] );
            $this->setError( "文件超过{$size}" );

            return $this->returnError();
        }

        //绝对路径
   //     $absolutePath = base_path() . 'public' . DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $this->param['path'] . DIRECTORY_SEPARATOR;
        //相对路径
        $relativePath = "public". DIRECTORY_SEPARATOR .'upload' . DIRECTORY_SEPARATOR . $this->param['path'] . DIRECTORY_SEPARATOR;

        //文件路径
        if ( ! empty( $this->param['merId'] ) ) {
        //    $absolutePath = base_path() . "/public/upload/merchant/{$this->param['merId']}/{$this->param['path']}/";
            $relativePath = "public". DIRECTORY_SEPARATOR ."upload". DIRECTORY_SEPARATOR ."merchant". DIRECTORY_SEPARATOR .$this->param['merId'] . DIRECTORY_SEPARATOR . $this->param['path'] . DIRECTORY_SEPARATOR;

        }

        //加入日期
    //    $absolutePath .= date( 'ymd' );
        $relativePath .= date( 'ymd' );


        //移动到目标文件夹
        $info = $file->store($relativePath);

        //上传失败
        if ( ! $info ) {
            $this->setError( $file->getError() );

            return $this->returnError();
        }

        $this->fileInfo     = $info;
        $this->relativePath = $relativePath;

        //上传成功的后续处理
        return $this->afterUploadSuccess();
    }



    /**
     * 上传成功的后续处理
     *
     * @return array
     */
    private function afterUploadSuccess() {
        //文件相对路径
        $this->relativePath .= DIRECTORY_SEPARATOR .$this->fileInfo ;

        //修改文件权限
        //chmod( $this->relativePath, 0777 );
        $mimes = Storage::mimeType($this->fileInfo);
        $size  = Storage::size($this->fileInfo);
        $name  = $this->fileInfo;

        //生成返回信息
        $this->result = [
            'uri'      => Storage::url($this->fileInfo),
            'savePath' => $this->relativePath,
            'mimes'    => $mimes,
            'size'     => $size,
            'name'     => $name
        ];

        //添加到上传列表
        $this->addUploadFtpList( $this->fileInfo );

        //如果是图片
        if ( $this->param['type'] == 'img' ) {
            $checkRet = $this->checkCropThumb();
            if ( ! $checkRet ) {
                unlink( $this->relativePath );

                return $this->returnError();
            }

            //保存到相册
            if ( ! $this->saveAsAlbum() ) {
                return $this->returnError();
            }
        }

        //上传到ftp
        if ( ! $this->uploadToFtp() ) {
            return $this->returnError();
        }


        return $this->returnSuccess();
    }

    /**
     * 添加到上传列表
     *
     * @param $filePath
     */
    private function addUploadFtpList( $filePath ) {
        $this->uploadFtpList[] = $filePath;
    }

    /**
     * 返回成功
     *
     * @return array
     */
    private function returnSuccess() {
        //如果是kind editor
        if ( $this->param['isKE'] ) {
            return [
                'error' => 0,
                'url'   => $this->result['uri']
            ];
        } else {
            return ajax_arr( '上传成功', 0, $this->result );
        }
    }

    /**
     * 返回错误
     *
     * @return array
     */
    private function returnError() {
        //如果是kind editor
        if ( $this->param['isKE'] ) {
            return [
                'error'   => 1,
                'message' => $this->error
            ];
        } else {
            return ajax_arr( $this->error, 500 );
        }
    }

    /**
     * 检查图片
     *
     * @return bool
     */
    private function checkCropThumb() {
        //打开图片
        $image                  = Image::make(Storage::path($this->fileInfo) );
        $this->result['width']  = $image->width();
        $this->result['height'] = $image->height();

        //检查是否要裁剪
        if ( $this->param['crop'] && ! empty( $this->param['crop'] ) ) {
            $pathInfo = pathinfo( $this->fileInfo );
            $cropPath = dirname(Storage::path($this->fileInfo))."crop_{$pathInfo['basename']}";

            //裁剪图片
            $crop = explode( ',', $this->param['crop'] );

            $image->crop( $crop[2], $crop[3], $crop[0], $crop[1])->save($cropPath );

            Image::make($cropPath)->resize($this->param['width'] , $this->param['height'])->save( Storage::path($this->fileInfo));

            $this->result['width']  = $this->param['width'];
            $this->result['height'] = $this->param['height'];
            unlink( $cropPath );
        } else {
            //检查图片尺寸
            if ( $this->param['width'] > 0 && $this->result['width'] != $this->param['width'] ) {
                $this->setError( '图片宽度需为' . $this->param['width'] );

                return FALSE;
            }

            if ( $this->param['height'] > 0 && $this->result['height'] != $this->param['height'] ) {
                $this->setError( '图片高度需为' . $this->param['height'] );

                return FALSE;
            }
        }

        //检查是否要生成 缩略图
        if ( ! empty( $this->param['thumb'] ) ) {

            $pathInfo = pathinfo( $this->fileInfo );
            $thumb    = explode( ',', $this->param['thumb'] );

            foreach ( $thumb as $size ) {
                $image   = Image::make(  $this->fileInfo );
                $sizeArr = explode( '|', $size );
                //计算尺寸 兼容 50 和 50|20 这2种模式
                $w = $sizeArr[0];
                $h = isset( $sizeArr[1] ) ? $sizeArr[1] : $sizeArr[0];

                $thumbPath = "{$pathInfo['dirname']}/{$pathInfo['filename']}.{$sizeArr[0]}.{$pathInfo['extension']}";

                $image->resize( $w, $h )->save( $thumbPath );
                $this->addUploadFtpList( $thumbPath );
                chmod( $thumbPath, 0777 );
            }
        }

        return TRUE;
    }

    /**
     * 上传到ftp
     *
     * @return bool
     */
    private function uploadToFtp() {

        if ( config( 'custom.uploadType' ) == 'ftp' ) {
            $ftp = new FtpService( config( 'custom.ftp' ) );
            foreach ( $this->uploadFtpList as $file ) {
                if ( $ftp->put( $file, $file ) ) {
                    unlink( $file );
                } else {
                    $this->setError( $ftp->get_error() );

                    return FALSE;
                }
            }
            $ftp->close();
        }

        return TRUE;
    }

    /**
     * 保存到相册
     *
     * @return bool
     */
    private function saveAsAlbum() {
        if ( $this->param['saveAsAlbum'] !== FALSE ) {
            $data = [
                'uri'      => $this->result['savePath'],
                'size'     => $this->result['size'],
                'img_size' => "{$this->result['width']}*{$this->result['height']}",
                'mimes'    => $this->result['mimes'],
            ];
            if ( ! empty( $this->param['merId'] ) ) {
                $data['mer_id'] = $this->param['merId'];
            }

            $MerAlbum = MerAlbumService::instance();
            $ret      = $MerAlbum->insert( $data, $this->param['albumTag'] );
            if ( $ret['code'] != 0 ) {
                $this->setError( $ret['msg'] );

                return FALSE;
            }
        }

        return TRUE;
    }


}
