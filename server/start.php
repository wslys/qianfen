<?php

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', True);

dirname($_SERVER['SCRIPT_FILENAME']).'/';

// 定义应用目录
define('APP_PATH', './Applications/');

// 引入IMServer的入口文件
require './vendor/bootstrap.php';
