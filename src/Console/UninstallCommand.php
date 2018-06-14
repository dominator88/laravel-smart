<?php

namespace Smart\Console\Commands;

use Illuminate\Console\Command;

class UninstallCommand extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'smart:uninstall';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = '卸载laravel-smart !';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		if (!$this->confirm('你确认要卸载laravel-smart吗?')) {
			return;
		}

		array_map(function ($file) {
			$this->call('migrate:rollback');
			$this->laravel['files']->deleteDirectory(app_path($file));
		}, config('backend.directory'));

		$this->laravel['files']->deleteDirectory(public_path('smart'));
		$this->laravel['files']->delete(config_path('backend.php'));
		$this->info('卸载laravel-smart 成功!');
		//
	}
}
