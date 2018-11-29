<?php
namespace Smart\Traits\Service;
use Illuminate\Database\Eloquent\Model;

trait Instance{

	//类实例
	//protected static $instance;

	private  $model;

    //得到当前服务
	public static function instance() {

		$class_name = get_class(); 
		app()->singleton($class_name, function ($app) use($class_name) {
		    $service =  new $class_name;
		    if(isset($service->model_class) && class_exists($service->model_class)){
		    	$service->model = new $service->model_class;
		    }
		   
		   return $service;
		});
		return  resolve($class_name);

	}
    
    protected  function getModel(){
        return self::instance()->model;
    }

    protected function setModel(Model $model){
    	self::instance()->model = $model;
    }

    
    
}