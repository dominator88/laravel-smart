<?php
namespace App\Api\Lib;

use Illuminate\Filesystem\Filesystem;

class Discover{
	
	$root_dir = app_path().'/Api';

	//获取版本
	public function version(){
		$filesystem = resolve('files');
		$dir = $this->root_dir.'/Service';
		//获取到版本号目录
		$dirs = $filesystem->directories($dir);
		$version = [];
		foreach($dirs as $version){
			array_push($version, $filesystem->name($dir));
		}
		return $version;

		function discover_path($dir) use ($filesystem){
			$dirs = $filesystem->directories($dir);

			foreach($dirs as $dir_children){
				$files = $filesystem->allFiles($dir_children);

				foreach($files as $file){
					$service = $filesystem->name($file);
					echo $service;
				}
				 
				discover_path($dir_children);		 
			}
			
		}
	}

	//获取版本所在目录
	public function dir($version){
		//列出版本下的目录

	}

	//获取服务类
	public function service(){

	}

}