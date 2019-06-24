<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 22:09
 */
namespace Smart\Service;

use Illuminate\Support\Facades\Auth;

class BaseService{

	 use \Smart\Traits\Service\ActionName;
	 
	protected $policies = [];

	protected function _checkPolicy($model){
    $policy = $this->getCurrentMethodName();
    if(!in_array($policy, $this->policies)){
      return true;
    }
    
    $user = Auth::user();
    if(!$user->can($policy, $model)){
      throw new \Exception('无权限对该内容进行操作');
    }
  }

}