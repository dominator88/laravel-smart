<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/28
 * Time: 13:25
 */
namespace Smart;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Smart\Models\SysModule;
use Smart\Extentions\EloquentUserProvider;
use Illuminate\Support\Facades\Auth;
use File;
use App;

class SmartServiceProvider extends ServiceProvider {

	protected $commands = [
		\Smart\Console\Commands\InstallCommand::class,
		\Smart\Console\Commands\UninstallCommand::class,
	];

	protected $routeMiddleware = [
		'auth.token' => \Smart\Middleware\CheckToken::class,
		'auth.permission' => \Smart\Middleware\Permission::class,
		'auth.cors' =>\Smart\Middleware\Cors::class,
		'auth.resetPassword' => \Smart\Middleware\ResetPassword::class,
	];

	public function boot() {

		$this->loadViewsFrom(__DIR__ . '/../resources/views', 'backend');

		if (config('backend.https')) {
            \URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

		$this->loadRoutesFrom(__DIR__ . '/../router/routes.php');

		$modules = explode(',', config('backend.module_ext'));
		//列出状态正常的模块  不可直接调用数据库

		foreach ($modules as $module) {
			//加载模块路由
			if (file_exists(app_path() . '/' . ucfirst($module) . '/routes.php')) {
				$this->loadRoutesFrom(app_path() . '/' . ucfirst($module) . '/routes.php');
			}

			//加载模块视图
			if (file_exists(app_path() . '/' . ucfirst($module) . '/views')) {

				$this->loadViewsFrom(app_path() . '/' . ucfirst($module) . '/views', ucfirst($module));
			}

			//加载模块迁移文件
			if(file_exists(app_path().'/'.ucfirst($module).'/migrations')){
				$this->loadMigrationsFrom(app_path().'/'.ucfirst($module).'/migrations');
			}

		}

		$this->publishes([__DIR__ . '/../config/' => config_path()], 'backend');

		//service
		$this->publishes([__DIR__ . '/../resources/Service' => app_path('Service')], 'backend');

		//Models
		$this->publishes([__DIR__ . '/../resources/Models' => app_path('Models')], 'backend');

		//发布Api包
		$this->publishes([__DIR__ . '/../resources/Api' => app_path('Api')], 'backend');

		if (file_exists(app_path('Api') . '/routes.php')) {
			$this->loadRoutesFrom(app_path('Api') . '/routes.php');
		}

		$this->publishes([__DIR__ . '/../resources/assets/static/' => public_path('static')], 'backend');

//		$this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'backend-migrations');
		$this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

		$this->publishes([__DIR__ . '/../resources/npm/' => public_path()], 'backend');

		//	$this->registerRoute();
	}

	public function register() {

		$this->mergeConfigFrom(__DIR__ . '/../config/backend.php', 'backend');

		$this->registerRouteMiddleware();

		$this->registerModuleRouteMiddleware();

		$this->registerProvider();

		$this->registerModuleProvider();

		$this->commands($this->commands);
	}

	/**
	 * Register the route middleware.
	 *
	 * @return void
	 */
	protected function registerRouteMiddleware() {
		// register route middleware.
		foreach ($this->routeMiddleware as $key => $middleware) {
			app('router')->aliasMiddleware($key, $middleware);
		}

	}

	protected function registerModuleRouteMiddleware(){
		$modules = explode(',', config('backend.module_ext'));

		foreach($modules as $module){
			if (file_exists(app_path() . '/' . ucfirst($module) . '/config.php')) {
				$module_lower = strtolower($module);
				$this->mergeConfigFrom(app_path() . '/' . ucfirst($module) . '/config.php',$module_lower);
				$middlewares = config($module_lower.'.middlewares');
				foreach($middlewares as $key => $middleware){
					app('router')->aliasMiddleware($module_lower.'.'.$key, $middleware);
				}
			}
		}
	}

	protected function registerProvider(){
		//注册认证服务
		Auth::provider('authenEloquent', function ($app, $config) {
            return new EloquentUserProvider($app->make('hash'), $config['model']);
        });

        //注册短信服务
        $this->app->singleton('sms' , function($app){
        	
        	return new \Overtrue\EasySms\EasySms($app['config']['sms']['sms']);
        });
	}

	protected function registerModuleProvider(){
		$modules = explode(',', config('backend.module_ext'));

		foreach($modules as $module){
			$dir = app_path() . '/' . ucfirst($module) . '/Providers';
			$this->loadProviders($dir);
			
		}
	}



	private function loadProviders($directory)
    {
        //$mainServiceProviderNameStartWith = 'Main';

        if (File::isDirectory($directory)) {

            $files = File::allFiles($directory);

            foreach ($files as $file) {
            	
                if (File::isFile($file)) {
                	$path = File::dirname($file);
	            	$startClass = substr($path,strrpos($path, 'app'));
	            	
	            	$name = File::name($file);
	            	
	            	$serviceProviderClass = str_replace('/','\\',ucfirst($startClass).'\\'.$name);
                	$this->loadProvider($serviceProviderClass);
                }
            }
        }
    }

    /**
     * @param $providerFullName
     */
    private function loadProvider($providerFullName)
    {
        App::register($providerFullName);
    }



}
