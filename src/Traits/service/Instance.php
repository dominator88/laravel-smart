<?php
namespace Smart\Traits\Service;

use Facades\Smart\Service\ServiceManager;
use Illuminate\Database\Eloquent\Model;

trait Instance{

	//类实例
	//protected static $instance;
	private $module;

	private  $model;

    //得到当前服务
	public static function instance() {

		$class_name = get_class();
		ServiceManager::bind($class_name);
		$instance = ServiceManager::make($class_name);
		//初始化module
		if(isset($instance->module_name) && $instance->module_name  && empty($instance->module)){
			$instance->setModule($instance->module_name);
		}
		//初始化Model
		if(isset($instance->model_class) && $instance->model_class  && empty($instance->model)){
			$instance->setModel(new $instance->model_class);
		}

		return $instance;
	}

	public function getModule(){
		return $this->module;
	}

	public function setModule($module){
		$module && $this->module = $module;
	}
    
    public  function getModel(){
        return $this->model;
    }

    public function setModel(Model $model){

    	$model && $this->model = $model;
    }

    
    
}