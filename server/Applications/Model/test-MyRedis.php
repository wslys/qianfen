<?php
/**
 * Created by PhpStorm.
 * Redis redis的使用测试
 * User: user
 * Date: 18-2-27
 * Time: 上午1:58
 */

use App\Model\MyRedis;

require_once "./MyRedis.php";

//实例化redis
$redis = new MyRedis();
//连接

//// 设置一个字符串的值
//$redis->set('cat', 111);
//
////获取一个字符串的值
//echo $redis->get('cat') . "\n"; // 111
//
//// 重复set
//$redis->set('cat', 222);
//echo $redis->get('runoobkey') . "\n"; // 222
//
//echo " =================== \n";
//
//$redis->hSet('hx', 'code', 101);
//$redis->hSet('hx', 'msg', 'success');
//$redis->hSet('hx', 'data', 'data');


$redis->hSetAll('wslttst', [
    'code' => '8080',
    'msg'  => 'success',
    'data' => 'data2'
]);

$arr = $redis->hGetAll('wslttst');
var_dump($arr);




