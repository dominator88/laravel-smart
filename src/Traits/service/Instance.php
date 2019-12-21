<?php
namespace Smart\Traits\Service;


use Illuminate\Database\Eloquent\Model;

trait Instance{

	//类实例
	//protected static $instance;

	private  $model;

	
	
	public static function instance(){
		$class_name = get_class();
	//	ServiceManager::bind($class_name);
	//	$instance = ServiceManager::make($class_name);
		$instance = resolve($class_name);

		if(isset($instance->model_class) && $instance->model_class  && empty($instance->model)){
			$model_class = $instance->model_class;
			$instance->setModel(new $model_class);
		}

		return $instance;
	}	
    
    public  function getModel(){
        return $this->model;
    }

    public function setModel(Model $model){

    	$model && $this->model = $model;
    }

    
    
}