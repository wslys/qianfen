<?php

namespace Core;

use Workerman\Worker;
use Core\Helper;
use Core\Env;

class Application {
	public static function run() {
		// 可以使用一次性定制做验证
		Env::run ();
		// 判断加载模块
		$loadModule = Helper::getAllConfig ( 'module' );
		// 自动加载模块
		foreach ( $loadModule as $moduleName => $module ) {
			if ($module ['onStart']) {
				// 自动生成文件夹与配置
				foreach ( $module ['worker'] as $worker ) {
					require_once APP_PATH . $moduleName . '/' . $worker . FILE_EXT;
				}
			}
		}
		Worker::runAll ();
	}
}