<?php 
namespace  Config;

/**
 * 数据库配置
 * Class Db
 * @package Config
 */
class Db{
    public static $db_conf = array(
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'user'     => 'root',
        'password' => '123456',
        'dbname'   => 'poker',
        'charset'  => 'utf8',
    );

}
