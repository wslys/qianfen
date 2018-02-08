<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18-2-7
 * Time: 下午9:16
 */

namespace App\Model;


class Init
{
    public static function init(){
        User::init();
        Room::init();
    }
}