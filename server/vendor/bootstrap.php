<?php
// 标记是全局启动
define('GLOBAL_START', 1);
//定义监控目录
define('FILE_MONITOR', realpath(APP_PATH));
// 记录开始运行时间
$GLOBALS['_beginTime'] = microtime(TRUE);
// 记录内存初始使用
define('MEMORY_LIMIT_ON', function_exists('memory_get_usage'));
if (MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
// 版本信息
const IM_VERSION = '1.0.0';
// 文件后缀
const FILE_EXT = '.php';
// 是否开启debug模式
if (APP_DEBUG) ini_set('display_errors', 'on');
// 自动加载composer类库
require_once __DIR__ . '/autoload.php';
// 自动检测环境以及模块
Core\Application::run();