<?php
require_once 'vendor/autoload.php';
$redis = new \App\Model\MyRedis([], []);
$redis->set('wsl', 'wsl value');

echo $redis->get('wsl');