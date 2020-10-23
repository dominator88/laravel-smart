<?php

namespace Smart\Console\Commands;

use Illuminate\Console\Command;
use Smart\Auth\Database\AdminTableSeeder;
use Smart\Models\SysUser;

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
            $this->initConfig($module);
            $this->initController($module);
            $this->initExampleController($module);
            $this->initMigration($module);
            $this->initMiddleware($module);
            $this->initService($module);
            $this->initProviders($module);
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

        $this->makeDir($module);
        $this->line('initDirectory success!');
        
        
    }

    /**
     * 初始化控制器
     * @param $module
     */
    public function initController($module){
        //基类
        $this->directory = app_path($module);
        $base_path = 'controllers/module.txt';
        $content = $this->getContent($base_path ,['module' => $module]);
        $this->makeDir('Controllers');
        $this->laravel['files']->put( app_path($module).'/Controllers/'.$module.'.php' , $content);
        $this->line('initController success!');

    }

    public function initExampleController($module){
        $example_path = 'controllers/index.txt';
        $content = $this->getContent($example_path , ['module' => $module]);
        $this->laravel['files']->put( app_path($module).'/Controllers/IndexController.php' , $content);
        $this->line('initExampleController success!');
    }

    public function initMigration($module){
        $this->makeDir('migrations');
        $this->line('init migration success!');
    }

    public function initService($module){
        $this->makeDir('Service');
        $this->line('init service success!');
    }

    public function initMiddleware($module){
        $this->makeDir('middlewares');
        $this->line('init middlewares success!');
    }

    public function initProviders($module){
        $this->directory = app_path($module);
        $this->makeDir('Providers');
        $providers_path = 'providers/mainproviders.txt';
        $content = $this->getContent($providers_path , ['module' =>  $module]);
        $this->laravel['files']->put( app_path($module).'/Providers/MainProvider.php' , $content);
        $this->line('init providers success!');
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

    public function initConfig($module){
        $config_path = 'config.txt';
        $content  = $this->getContent($config_path , ['module' => $module]);
        $this->laravel['files']->put(app_path($module).'/config.php' , $content);
        $this->line('initConfig success!');
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

