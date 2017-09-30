<?php
/**
 * Created by PhpStorm.
 * User: MR.Z < zsh2088@gmail.com >
 * Date: 2017/9/28
 * Time: 13:25
 */
namespace Smart;

use Illuminate\Support\ServiceProvider;

class SmartServiceProvider extends ServiceProvider{

    public function boot(){

        $this->loadViewsFrom( __DIR__.'/../resources/views' ,'backend');

        $this->loadRoutesFrom( __DIR__ . '/../router/routes.php');

        $this->publishes([ __DIR__.'/../config/' => config_path()] , 'backend');




        $this->publishes([ __DIR__.'/../resources/Api' => app_path('Http/Controllers/Api')] , 'backend');

        $this->publishes([ __DIR__.'/../resources/assets/' => public_path('static')] , 'backend');


    }

    public function register(){
        $this->mergeConfigFrom( __DIR__.'/../config/backend.php' ,'backend' );
    }

}

