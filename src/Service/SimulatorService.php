<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/18
 * Time: 17:06
 */
namespace Smart\Service;
use Smart\Lib\Discover;

/**
 * 接口模拟器 Service
 *
 * @author Zix
 * @version 2.0 2016-09-13
 */

use Illuminate\Filesystem\Filesystem;
use ReflectionClass;

class SimulatorService extends BaseService {

    use \Smart\Traits\Service\Instance;

    private $apiVer = [
        'v1' => 'v1',
        'v2' => 'v2',
    ];

    function readApi( $apiVersion ) {
        $discover = new Discover;
        $services = $discover->service($apiVersion);
        return $services;
        
    }

    public function readVersion(){
        return $this->apiVer;
    }

    
}