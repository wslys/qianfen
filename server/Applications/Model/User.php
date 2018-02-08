<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-7
 * Time: 上午12:43
 */

namespace App\Model;


class User extends Model
{
    public static $table = "user";

    public static function init() {
        parent::$table = self::$table;
    }
}