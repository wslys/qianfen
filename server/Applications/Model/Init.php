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
    /**
     * TODO 初始化Model  [User, Room ...... ]
     */
    public static function init(){
        User::init();
        Room::init();
    }
}