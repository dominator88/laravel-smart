<?php
namespace Smart\Lib;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use ReflectionClass;

class Discover{
	
	private $root_dir;

	private $filesystem;

	public function __construct(){
		$this->root_dir = app_path().'/Api';
		$this->filesystem = resolve('files');
	}

	//获取版本
	public function version(){
		$dir = $this->root_dir.'/Service';

		$dirs = $this->filesystem->directories($dir);
		$data = [];
		foreach($dirs as $dir_children){
			$group = substr($dir_children, strrpos($dir_children,'/')+1);
			$readme = $dir.'/'.$group.'/readme.md';
			if($this->filesystem->exists($readme)){
				$tmp = json_decode($this->filesystem->get($readme),true);
				$data[$tmp['group'] ][] = $tmp;
			}
			
		}
		return $data;
		
	}

	//获取版本所在目录
	public function dir($version){
		//列出版本下的目录

	}

	//获取服务类
	public function service($version){

		$dir   = app_path('Api') . '/Service/' . $version;
		$files = $this->filesystem->allFiles($dir);
		
		$class_func = function($file_path){
			$class_tmp = Str::after($file_path, base_path());
			$class_arr = explode('/' ,$class_tmp);
			$class_arr = array_filter($class_arr);
			$class_arr = array_map(function($val){
				return Str::studly($val);
			}, $class_arr);
			return Str::before( join('\\', $class_arr), '.php');
		};
		$data = [];
		foreach($files as $file){

			$filename = $file->getRelativePathname();
			if(!Str::contains($filename, 'Service.php')) continue;
            $class = substr( $filename, 0, strripos($filename , 'Service.php'));

			$class = $class_func($file);
			$name = $this->_parser($class);
			$directory = $this->filesystem->dirname($file);
			
			$dir = substr($directory , strrpos($directory,'/')+1);
			$action = strtolower(Str::before($this->filesystem->name($file),'Service'));
			
			$data[$dir][] = ['directory' => $dir, 'action' =>$action , 'text'=> $name];
			
		}
		return $data;

		/*$discover_path = function ($dir) use (&$discover_path, $filesystem){
			$dirs = $filesystem->directories($dir);

			foreach($dirs as $dir_children){
				$version = substr($dir_children, strrpos($dir_children,'/')+1);
			//	substr($dir_children,)
				$files = $filesystem->allFiles($dir_children);

				foreach($files as $file){
					$service = $filesystem->name($file);
				//	echo $service."\n";
				}
				 
				$discover_path($dir_children);		 
			}
			
		};
		$tmp = $discover_path($dir);
		//获取到版本号目录
		$dirs = $filesystem->directories($dir);

		$version = [];
		foreach($dirs as $version){
			array_push($version, $filesystem->name($dir));
		}
		return $version;*/
	}

	private function _parser($class){
		$ref = new ReflectionClass( $class );
        $doc = $ref->getDocComment();
        preg_match( '#^/\*\*(.*)\*/#s', $doc, $comment );
        $comment = trim( $comment [1] );
        preg_match_all( '#^\s*\*(.*)#m', $comment, $lines );

        $name = trim( $lines[1][0] );
        preg_match_all( '/@deprecated([^@]*)/', $comment, $matches );

        if ( empty( $matches[0] ) ) {
            return $name;
        } else {
            return FALSE;
        }
	}

}