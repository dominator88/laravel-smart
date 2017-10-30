<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/28
 * Time: 13:25
 */
namespace Smart;


use App\Service\MerTokenService;
use Illuminate\Support\ServiceProvider;
use Smart\Interfaces\TokenService;

class SmartServiceProvider extends ServiceProvider{

    protected  $commands = [
        \Smart\Console\Commands\InstallCommand::class,
        \Smart\Console\Commands\UninstallCommand::class,
    ];

    public function boot(){

        $this->loadViewsFrom( __DIR__.'/../resources/views' ,'backend');

        $this->loadRoutesFrom( __DIR__ . '/../router/routes.php');

        $this->publishes([ __DIR__.'/../config/' => config_path()] , 'backend');

        if(!file_exists(app_path('Http/Controllers/Api/Service'))){
            mkdir(app_path('Http/Controllers/Api/Service') , 0777 ,true);
            chmod(app_path('Http/Controllers/Api/Service') , 0777);
        }

        //service
        $this->publishes([ __DIR__.'/../resources/Service' => app_path('Service')] , 'backend');

        //Models
        $this->publishes([ __DIR__.'/../resources/Models' => app_path('Models')] , 'backend');

        $this->publishes([ __DIR__.'/../resources/Api' => app_path('Http/Controllers/Api')] , 'backend');

        $this->publishes([ __DIR__.'/../resources/assets/static/' => public_path('static')] , 'backend');

        $this->publishes([ __DIR__.'/../database/migrations/' => database_path( 'migrations')] , 'backend-migrations');

        $this->publishes([ __DIR__.'/../resources/npm/' => public_path()] , 'backend');

    }

    public function register(){
        $this->app->singleton( TokenService::class , function($app){
            return new MerTokenService();
        });

        $this->mergeConfigFrom( __DIR__.'/../config/backend.php' ,'backend' );
        $this->commands($this->commands);
    }

}

