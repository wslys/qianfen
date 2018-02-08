<?php
/**
 * Created by PhpStorm
 * User: wsl
 * Date: 2018/2/5
 * Time: 12:08
 */

// TODO 暂时未用
return [
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => '3306',
        'user'     => 'root',
        'password' => '',
        'db_name'  => 'poker_db'
    ],
    'worker' => [
        'count' => 1, // 进程数
        'scoket_name' => 'websocket://127.0.0.1:1346'

    ],
    'app' => [
        'system_secret_key' => '770fed4ca2aabd20ae9a5dd774711de2'
    ]
];