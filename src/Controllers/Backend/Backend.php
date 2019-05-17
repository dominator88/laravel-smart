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

    

    public function __construct(Request $request)
    {   
        parent::__construct($request);
        $this->middleware('auth');

    }

    

    public function _init($pageTitle = '新页面'){
        
        parent::_init($pageTitle);

        
        $SysFuncService = ServiceManager::make(SysFuncService::class );


      $this->user = Auth::user();
    
        $sysRole = $this->user->sysRole;
        $roles = $sysRole->pluck('id')->toArray();

        $menuData = [];

        if(Auth::id() == config('backend.superAdminId')){
            $menuData = $SysFuncService->getByCond(['isMenu' => 1, 'status' => 1, 'module' => $this->module]);
        }else{
            $menuData = $SysFuncService->getMenuByRole(
                $roles,
                ucfirst($this->module) );
        }
        $this->_addData('menuData',$menuData);
        $this->_addData( 'user', $this->user );

    }

    public function _displayWithLayout( $view = 'index'){

        return view($view)->with($this->data)->with('js' , $this->_makeJs())->with('css' , $this->_makeCss());
    }
}