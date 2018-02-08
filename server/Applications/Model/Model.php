<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-7
 * Time: 上午12:43
 */

namespace App\Model;


use App\IM\Events;
use Exception;

class Model
{
    public static $table = '';

    public static function init() {}

    public static function setTable($table='') {
        self::$table = isset($table)?$table:self::$table;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function create($data = []) {
        if (!$data) {
            return false;
        }
        return Events::$db->insert(self::$table)->cols($data)->query();
        /*return Events::$db->insert(self::table)
            ->cols(array(
                'open_id'    => isset($data['open_id'])?$data['open_id']:'',      // OpenID
                'nick_name'  => isset($data['nick_name'])?$data['nick_name']:'',  // 用户昵称
                'avatar_url' => isset($data['avatar_url'])?$data['avatar_url']:'',// 用户头像
                'gender'     => isset($data['gender'])?$data['gender']:'',        // 用户 男女
                'city'       => isset($data['city'])?$data['city']:'',            // 城市
                'province'   => isset($data['province'])?$data['province']:'',    // 省份
                'country'    => isset($data['country'])?$data['country']:'',      // 国家
                'language'   => isset($data['language'])?$data['language']:'',    // 语言
                'create_at'  => time(), // 创建时间
                'update_at'  => time()  // 修改时间
            ))
            ->query();*/
    }

    /**
     * @param array $data
     * @param $id
     * @return bool
     */
    public static function update($data = [], $id) {
        if (!$data) {
            return false;
        }

        $cols_arr = [];
        $values_arr = [];
        foreach ($data as $key=>$val) {
            $cols_arr[] = $key;
            $values_arr[] = $val;
        }

        return Events::$db->update(self::table)
            ->cols($cols_arr)
            ->where("id=$id")
            ->bindValue(implode(",", $values_arr))->query();
    }

    /**
     * @param $param
     * @return mixed
     */
    public static function findOne($param) {
        self::init();
        if (!is_array($param)) {
            return Events::$db->select('*')
                ->from(self::$table)
                ->where('id=:id')
                ->bindValues(['id' => $param])
                ->row();
        }else {
            $where_str = '';
            $bind_values_str = [];
            foreach ($param as $key=>$val) {
                $where_str .= " " . $key . "=:" . $key . " AND";
                $bind_values_str[$key] = $val;
            }
            $where_str = substr($where_str, 0, count($where_str) - 4);

            return Events::$db->select('*')
                ->from(self::$table)
                ->where("$where_str")
                ->bindValues($bind_values_str)
                ->row();
        }
    }

    /**
     * @param $param
     * @return mixed
     */
    public static function findAll($param) {
        if (is_array($param)) {
            $where_str = '';
            $bind_values_str = [];
            foreach ($param as $key=>$val) {
                $where_str .= $key . "=:" . $key . " AND";
                $bind_values_str[$key] = $val;
            }
            $where_str = substr($where_str, 0, count($where_str) - 4);
            return Events::$db->select('*')
                ->from(self::table)
                ->where("$where_str")
                ->bindValues($bind_values_str)
                ->query();
        }
        return Events::$db->select('*')
            ->from(self::table)
            ->query();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function delete($id) {
        return Events::$db->delete(self::table)->where("id=$id")->query();
    }

    public static function counts() {
        $result = Events::$db->select('COUNT(*) AS num')
            ->from(self::table)
            ->row();
        return $result['num'];
    }


    /**
     * TODO 如果使用类的实例调用$method，但$method方法不是公有的，就会触发此函数。
     * @param $method
     * @param $args
     * @return mixed
     * @throws Exception
     */
    public function __call($method, $args) {
        echo "\njinru=============================1\n";
        // 检查是否存在方法$method
        if (method_exists($this, $method)) {
            echo "\njinru=============================2\n";
            $before_method = '__' + $method;
            // 检查是否存在方法$before_method
            if (method_exists($this, $before_method)) {
                echo "\njinru=============================3\n";
                // 调用$before_method，检查其返回值，决定是否跳过函数执行
                if (call_user_func_array(array($this, $before_method), $args)) {
                    echo "\njinru=============================4\n";
                    return call_user_func_array(array($this, $method), $args);
                }
            } else {
                echo "\njinru=============================5\n";
                // $before_method不存在，直接执行函数
                return call_user_func_array(array($this, $method), $args);
            }
        } else {
            echo "\njinru=============================6\n";
            throw new Exception('no such method ' . $method);
        }
    }
}

