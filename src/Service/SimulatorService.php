<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 17:06
 */
namespace Smart\Service;

/**
 * 接口模拟器 Service
 *
 * @author Zix
 * @version 2.0 2016-09-13
 */

use Illuminate\Filesystem\Filesystem;
use ReflectionClass;

class SimulatorService extends BaseService {

    //类实例
    private static $instance;

    //生成类单例
    public static function instance() {
        if ( self::$instance == NULL ) {
            self::$instance = new SimulatorService();
        }

        return self::$instance;
    }

    function readApi( $apiVersion ) {

        $dir   = app_path('Api') . '/Service/' . $apiVersion;
        $filesystem = new Filesystem();
        $dirs = $filesystem->directories($dir);

        $api   = [];

        foreach( $dirs as $dir ){
            $files = $filesystem->allFiles($dir);
            foreach($files  as $file){
                $filename = $file->getRelativePathname();
                $class = substr( $filename, 0, strripos($filename , 'Service.php'));
                $file_dir = $filesystem->dirname($file);
                $file = substr($file_dir , strripos($file_dir , '/' ) + 1) ;
                $name = $this->_parser( $apiVersion ,$file , $class);
                if ( ! $name ) {
                    continue;
                }
                $api[ $file ][] = [
                    'directory' => $file,
                    'action'    => strtolower( $class ),
                    'text'      => $name
                ];
            }
        }



        return $api;
    }

    function _parser( $apiVersion, $subDir, $className ) {
        $api = "App\\Api\\Service\\{$apiVersion}\\{$subDir}\\{$className}Service";
        //echo $api;
        $ref = new ReflectionClass( $api );
        $doc = $ref->getDocComment();
        preg_match( '#^/\*\*(.*)\*/#s', $doc, $comment );
        $comment = trim( $comment [1] );
        preg_match_all( '#^\s*\*(.*)#m', $comment, $lines );

        $name = trim( $lines[1][0] );
        preg_match_all( '/@deprecated([^@]*)/', $comment, $matches );

        if ( empty( $matches[0] ) ) {
            return $name;
        } else {
            return FALSE;
        }
    }
}