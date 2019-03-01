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
use Facades\Smart\Service\ServiceManager;
use Mp\Facades\Widget;

class Backend extends SysBase{

    protected $autoload_service = 1;

    public function __construct(Request $request)
    {   
        parent::__construct($request);
        $this->autoload_service && $this->_initService();
        $this->middleware('auth');
    }

    private function _initService(){

        $this->service = ServiceManager::make( 'Smart\\Service\\'.$this->controller.'Service');
    }

    public function _init($pageTitle = '新页面'){
        
        parent::_init($pageTitle);
        $SysFuncService = ServiceManager::make(SysFuncService::class );

        $jsCode = <<<EOF
            {$this->controller}.init();
EOF;

        $this->_addJsCode($jsCode);
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

        return view($view)->with($this->data)->with('js' , $this->_makeJs())->with('css' , $this->_makeCss());
    }
}