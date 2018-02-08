<?php
namespace  Core;
class  Env{
	public  static  function run (){
		self::extension();
		self::mysql();
		self::other(); 
	}

	// TODO
	public static function extension(){
		/*if(strpos(strtolower(PHP_OS), 'win') === 0)
		{
			exit("start.php not support windows, please use start_for_win.bat\n");
		}
	
		// 检查扩展
		if(!extension_loaded('pcntl'))
		{
			exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
		}
	
		if(!extension_loaded('posix'))
		{
			exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
		}*/
	
	}
	//检测mysql版本
	public  static function mysql(){
		 return true;
	}
	public static function other(){
		return true;
	}
}

