<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/28
 * Time: 13:25
 */
namespace Smart;

use App\Service\MerTokenService;
use App\Service\SysTokenService;
use Illuminate\Support\ServiceProvider;

use Smart\Interfaces\TokenService;

use Illuminate\Mail\Mailer;
use Facades\Smart\Service\ServiceManager;

class SmartServiceProvider extends ServiceProvider{

    protected  $commands = [
        \Smart\Console\Commands\InstallCommand::class,
        \Smart\Console\Commands\UninstallCommand::class,
    ];

    protected $routeMiddleware = [
        'auth.token' => \Smart\Middleware\CheckToken::class
    ];

    public function boot(){

        $this->loadViewsFrom( __DIR__.'/../resources/views' ,'backend');

        $this->loadRoutesFrom( __DIR__ . '/../router/routes.php');

        $modules = explode(',' , config('backend.module_ext'));

        foreach($modules as $module){
            if(file_exists(app_path().'/'.ucfirst($module).'/routes.php')) {
                $this->loadRoutesFrom(app_path() . '/' . ucfirst($module) . '/routes.php');
            }

            if(file_exists(app_path().'/'.ucfirst($module).'/views')){
                $this->loadViewsFrom(app_path().'/'.ucfirst($module) .'/views' , ucfirst($module));
            }

        }

        $this->publishes([ __DIR__.'/../config/' => config_path()] , 'backend');

/*        if(!file_exists(app_path('Http/Controllers/Api/Service'))){
            mkdir(app_path('Http/Controllers/Api/Service') , 0777 ,true);
            chmod(app_path('Http/Controllers/Api/Service') , 0777);
        }*/

        //service
        $this->publishes([ __DIR__.'/../resources/Service' => app_path('Service')] , 'backend');

        //Models
        $this->publishes([ __DIR__.'/../resources/Models' => app_path('Models')] , 'backend');

        //发布Api包
        $this->publishes([ __DIR__.'/../resources/Api' => app_path('Api')] , 'backend');

        if( file_exists(app_path('Api').'/routes.php' ) ){
            $this->loadRoutesFrom(app_path('Api').'/routes.php');
        }

        $this->publishes([ __DIR__.'/../resources/assets/static/' => public_path('static')] , 'backend');

        $this->publishes([ __DIR__.'/../database/migrations/' => database_path( 'migrations')] , 'backend-migrations');

        $this->publishes([ __DIR__.'/../resources/npm/' => public_path()] , 'backend');



    }



    public function register(){

        $this->app->singleton( ServiceManager::class ,function($app){
            return new ServiceManager();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/backend.php' ,'backend' );
        $this->registerRouteMiddleware();
        $this->commands($this->commands);
    }


    /**
     * Register the route middleware.
     *
     * @return void
     */
    protected function registerRouteMiddleware()
    {
        // register route middleware.
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

    }

}

