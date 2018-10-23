<?php

namespace Smart\Console\Commands;

use Illuminate\Console\Command;
use Smart\Auth\Database\AdminTableSeeder;
use Smart\Models\SysUser;
use Smart\Models\SysModule;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smart:install {module?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install the CMF Command';

    protected $directory = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = ucfirst($this->input->getArgument('module'));
        if($module){
            $this->initDirectory($module);
            $this->initView( $module );
            $this->initRoutes($module);
            $this->initController($module);
            $this->initExampleController($module);
        }else{

         $this->initData($module);
        }
        //
        $this->info('laravel-smart install success!');
    }

    /**
     * 初始化数据
     */
    public function initData($module){


            //默认安装基础数据
            $this->call('migrate');
            if( SysUser::count() == 0){
                $this->call('db:seed' , [ '--class' => AdminTableSeeder::class]);
            }

        $this->line('initData success!');

    }

    /**
     * 初始化模块目录
     * @param $module
     */
    public function initDirectory($module){
        if($module){
            SysModule::FirstOrCreate(['name'=>$module , 'symbol'=>strtolower($module),'displayorder'=>0,'version'=>'1.0','author'=>'MR.Z','status'=>1]);
        }else{
            $this->makeDir($module);
            $this->line('initDirectory success!');
        }
        
    }

    /**
     * 初始化控制器
     * @param $module
     */
    public function initController($module){
        //基类
        $this->directory = app_path($module);
        $base_path = 'Controllers/module.txt';
        $content = $this->getContent($base_path ,['module' => $module]);
        $this->makeDir('Controllers');
        $this->laravel['files']->put( app_path($module).'/Controllers/'.$module.'.php' , $content);
        $this->line('initController success!');

    }

    public function initExampleController($module){
        $example_path = 'Controllers/index.txt';
        $content = $this->getContent($example_path , ['module' => $module]);
        $this->laravel['files']->put( app_path($module).'/Controllers/IndexController.php' , $content);
        $this->line('initExampleController success!');
    }


    /**
     * 初始化视图
     * @param $module
     */
    public function initView($module){
        $this->directory = app_path($module);
        $this->makeDir('views');
        $view_path = 'views/Index/index.txt';
        $this->makeDir('views/Index');
        $content = $this->getContent($view_path , ['module' =>  $module]);

        $this->laravel['files']->put( app_path($module).'/views/Index/index.blade.php' , $content);
        $this->line('initView success!');
    }

    /**
     * 初始化模块路由文件
     * @param $module
     */
    public function initRoutes($module){
        $route_path = 'routes.txt';
        $content  = $this->getContent($route_path , ['module' => $module]);
        $this->laravel['files']->put(app_path($module).'/routes.php' , $content);
        $this->line('initRoutes success!');

    }

    protected  function getContent($path , $param){
        $template_path = __DIR__.'/../../templates/module/'.$path;
        $tmp_content = $this->laravel['files']->get($template_path);
        $param_keys = array_keys($param);
        $param_keys = array_map(function($i){ return '{'.$i.'}';},$param_keys );

        return str_replace( $param_keys , array_values($param) ,$tmp_content );


    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}

