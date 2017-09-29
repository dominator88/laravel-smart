<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 22:09
 */
namespace Smart\Service;

use Illuminate\Database\Eloquent\Model;

class BaseService{
    private  $model = null;


    public  function getModel(){
        return $this->model;
    }
    
    public function setModel(Model $model){
        return $this->model = $model;
    }
}