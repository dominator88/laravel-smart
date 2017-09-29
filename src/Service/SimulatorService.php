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

        $dir   = app_path() . "/Http/Controllers/api/service/" . $apiVersion;
        $files = scandir( $dir );

        $api   = [];
        foreach ( $files as $file ) {

            if ( strpos( $file, '.' ) > - 1 ) {
                continue;
            }

            if ( ! array_key_exists( $file, $api ) ) {
                $api[ $file ] = [];
            }

            $basePaths = $dir . DIRECTORY_SEPARATOR . $file;
            if ( is_dir( $basePaths ) ) {
                $basePathFiles = scandir( $basePaths );
                foreach ( $basePathFiles as $f ) {
                    if ( strpos( $f, '.' ) == 0 ) {
                        continue;
                    }
                    $f_path = $basePaths . DIRECTORY_SEPARATOR . $f;
                    if ( is_file( $f_path ) ) {
                        if ( strpos( $f, '.php' ) > 0 ) {
                            $fileName = substr( $f, 0, strlen( $f ) - strlen( 'Service.php' ) );
//							$f_name    = "Api\\Service\\{$apiVersion}\\$file\\" . $fileName;

                            $name = $this->_parser( $apiVersion, $file, $fileName );
                            if ( ! $name ) {
                                continue;
                            }
                            $api[ $file ][] = [
                                'directory' => $file,
                                'action'    => strtolower( $fileName ),
                                'text'      => $name
                            ];
                        }
                    }
                }
            }
        }

        return $api;
    }

    function _parser( $apiVersion, $subDir, $className ) {
        $api = "App\\Http\\Controllers\\Api\\Service\\{$apiVersion}\\{$subDir}\\{$className}Service";
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