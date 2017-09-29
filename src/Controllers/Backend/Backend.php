<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 2017/9/14
 * Time: 09:45
 */
namespace Smart\Controllers\Backend;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Smart\Service\SysFuncService;

class Backend extends SysBase{



    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware('auth');

    }

    public function _init($pageTitle = '新页面'){
        parent::_init($pageTitle);
        $SysFuncService = SysFuncService::instance();
      //  var_dump($SysFuncService->getMenuByRoles(1,'backend'));
        $this->user = Auth::user();
        $this->_addData(
            'menuData',
        //暂定超级管理员
            $SysFuncService->getMenuByRoles(
                Auth::id(),
                $this->module )
        );
        $this->_addData( 'user', $this->user );

    }

    public function _displayWithLayout( $view = 'index'){

        foreach($this->data as $k=>$v){
            view()->share($k , $v);
        }
        view()->share('js' , $this->_makeJs());
        view()->share('css' , $this->_makeCss());

        return view($view);
    }
}