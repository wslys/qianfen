<?php
namespace  Core;
class Helper{
    public static $conf = array ();
    //加载启动模块配置
    static public function getConfig($name, $file) {
        $path = APP_PATH. 'Config/' . $file . FILE_EXT;
        if (isset ( self::$conf [$file] )) {
            return self::$conf [$file] [$name];
        } else {
            if (is_file ( $path )) {
                $conf = include $path;
                if (isset ( $conf [$name] )) {
                    self::$conf [$file] = $conf;
                    return $conf [$name];
                } else {
                    new \Exception ( '不存在这个配置项目' . $name );
                }
            } else {
                new \Exception ( '找不到配置文件' . $file );
            }
        }
    }
    static public function getAllConfig($file) {
        $path = APP_PATH. 'Config/' . $file .FILE_EXT;
        if (isset ( self::$conf [$file] )) {
            return self::$conf [$file];
        } else {
            if (is_file ( $path )) {
                $conf = include $path;
                self::$conf [$file] = $conf;
                return $conf;
            } else {
                new \Exception ( '找不到配置文件' . $file );
            }
        }
    }
}