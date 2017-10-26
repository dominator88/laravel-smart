<?php

namespace Smart\Console\Commands;

use Illuminate\Console\Command;
use Smart\Models\SysUser;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smart:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'install the CMF Command';

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
        $this->initData();
        //
        $this->info('laravel-smart 安装成功!');
    }

    /**
     * 初始化数据
     */
    public function initData(){

        $this->call('migrate');
        if( SysUser::count() == 0){
            $this->call('db:seed' , [ '--class' => \AdminTableSeeder::class]);
        }

    }
}
