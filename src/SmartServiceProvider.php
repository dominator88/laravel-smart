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

class SmartServiceProvider extends ServiceProvider {

	protected $commands = [
		\Smart\Console\Commands\InstallCommand::class,
		\Smart\Console\Commands\UninstallCommand::class,
	];

	protected $routeMiddleware = [
		'auth.token' => \Smart\Middleware\CheckToken::class,
		'auth.permission' => \Smart\Middleware\Permission::class,
		'auth.cors' =>\Smart\Middleware\Cors::class,
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
			if (file_exists(app_path() . '/' . ucfirst($module) . '/routes.php')) {
				$this->loadRoutesFrom(app_path() . '/' . ucfirst($module) . '/routes.php');
			}

			if (file_exists(app_path() . '/' . ucfirst($module) . '/views')) {

				$this->loadViewsFrom(app_path() . '/' . ucfirst($module) . '/views', ucfirst($module));
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

	/**
	 *	Register the route
	 *
	 *	@return void
	 */
	/*protected function registerRoute() {
		//列出启用模块
		$sysModuleService = ServiceManager::make(SysModuleService::class);
		$sysModules = $sysModuleService->getByCond(['status' => 1]);
		$modules = array_column($sysModules, 'symbol');
		//	return $sysModules;
		$sysFuncService = ServiceManager::make(sysFuncService::class);
		$sysFuncs = $sysFuncService->getModel()->with('sysFuncPrivileges')->whereIn('module', $modules)->where('status', 1)->whereNotNull('uri')->where('uri', '<>', '')->get();
		//列出功能
		//针对功能定义路由规则
		//prefix namespace middleware  别名
		//规则 index read insert update destory

		foreach ($sysFuncs as $func) {
			$uri = $func->uri;
			$param = explode('/', $uri);
			$module = $param[0];
			$func_str = $param[1];
			if ($func_str == 'index') {
				continue;
			}
			foreach ($func->sysFuncPrivileges as $funcPrivilege) {
				if ($module == 'backend') {

					if ($funcPrivilege->name == 'delete') {
						Route::post($uri, 'Smart\Controllers\Backend\\' . $func_str . '@destroy')->middleware('web');
					} elseif (in_array($funcPrivilege->name, ['index', 'read'])) {

						Route::get($uri, 'Smart\Controllers\Backend\\' . $func_str . '@' . $funcPrivilege->name)->middleware('web');

					} else {
						Route::post($uri, 'Smart\Controllers\Backend\\' . $func_str . '@' . $funcPrivilege->name)->middleware('web');
					}
				}

			}

		}

	}*/

}
